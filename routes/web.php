<?php

use App\Http\Controllers\Blog\BlogCategoryShowController;
use App\Http\Controllers\Blog\BlogIndexController;
use App\Http\Controllers\Blog\BlogShowController;
use App\Http\Controllers\Blog\BlogTagShowController;
use App\Http\Controllers\NewsletterConfirmationController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\NewsletterUnsubscribeController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', function () {
    $headline = BlogPost::query()
        ->with(['category', 'featuredImage'])
        ->published()
        ->orderByDesc('is_headline')
        ->latest('published_at')
        ->first();

    $headlineKey = $headline?->getKey();

    $featuredPosts = BlogPost::query()
        ->with('category')
        ->published()
        ->where('is_featured', true)
        ->when($headlineKey, fn ($builder) => $builder->whereKeyNot($headlineKey))
        ->latest('published_at')
        ->limit(3)
        ->get()
        ->map(fn (BlogPost $post): array => [
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'tag' => $post->category->name,
            'categorySlug' => $post->category->slug,
            'readTime' => ($post->reading_time_minutes ?? 1).' min',
            'accent' => $post->category->accent,
        ])
        ->values();

    $latestPosts = BlogPost::query()
        ->with('category')
        ->published()
        ->when($headlineKey, fn ($builder) => $builder->whereKeyNot($headlineKey))
        ->latest('published_at')
        ->limit(4)
        ->get()
        ->map(fn (BlogPost $post): array => [
            'slug' => $post->slug,
            'title' => $post->title,
            'date' => $post->published_at->format('M j, Y'),
            'dateTime' => $post->published_at->toDateString(),
            'category' => $post->category->name,
            'categorySlug' => $post->category->slug,
        ])
        ->values();

    $headlinePost = $headline ? [
        'slug' => $headline->slug,
        'title' => $headline->title,
        'excerpt' => $headline->excerpt,
        'category' => $headline->category->name,
        'categorySlug' => $headline->category->slug,
        'accent' => $headline->category->accent,
        'date' => $headline->published_at->format('M j, Y'),
        'dateTime' => $headline->published_at->toDateString(),
        'readTime' => ($headline->reading_time_minutes ?? 1).' min',
        'featuredImageUrl' => $headline->featuredImage
            ? $headline->featuredImage->url()
            : ($headline->featured_image_path ? Storage::disk('public')->url($headline->featured_image_path) : null),
    ] : null;

    $organization = SeoPayload::organization();

    $seo = SeoPayload::make([
        'canonical' => route('home'),
        'jsonLd' => [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    'name' => (string) config('seo.site_name'),
                    'url' => SeoPayload::absoluteUrl('/'),
                    'description' => (string) config('seo.default_description'),
                    'publisher' => $organization,
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => SeoPayload::absoluteUrl('/blog').'?q={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
                $organization,
            ],
        ],
    ]);

    return Inertia::render('Welcome', [
        'featuredPosts' => $featuredPosts,
        'latestPosts' => $latestPosts,
        'headlinePost' => $headlinePost,
        'seo' => $seo,
    ]);
})->name('home');
Route::inertia('/contact', 'Contact')->name('contact');

Route::get('/newsletter', [NewsletterSubscriptionController::class, 'create'])->name('newsletter');
Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])
    ->middleware('throttle:newsletter-subscription')
    ->name('newsletter.store');
Route::get('/newsletter/resend', [NewsletterSubscriptionController::class, 'createResend'])->name('newsletter.resend');
Route::post('/newsletter/resend', [NewsletterSubscriptionController::class, 'resend'])
    ->middleware('throttle:newsletter-resend')
    ->name('newsletter.resend.store');
Route::get('/newsletter/confirm/{token}', NewsletterConfirmationController::class)
    ->name('newsletter.confirm')
    ->where('token', '[A-Za-z0-9]+');
Route::get('/newsletter/unsubscribe/{token}', NewsletterUnsubscribeController::class)
    ->name('newsletter.unsubscribe')
    ->where('token', '[A-Za-z0-9]+');

Route::get('/blog', BlogIndexController::class)->name('blog.index');
Route::get('/blog/category/{slug}', BlogCategoryShowController::class)->name('blog.category');
Route::get('/blog/tag/{slug}', BlogTagShowController::class)->name('blog.tag');
Route::get('/blog/{slug}', BlogShowController::class)->name('blog.show');

Route::inertia('/legal/privacy', 'Legal/PrivacyPolicy')->name('legal.privacy');
Route::inertia('/legal/terms', 'Legal/TermsOfService')->name('legal.terms');
Route::inertia('/legal/cookies', 'Legal/CookiePolicy')->name('legal.cookies');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
