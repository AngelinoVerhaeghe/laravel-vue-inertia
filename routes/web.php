<?php

use App\Http\Controllers\Blog\BlogIndexController;
use App\Http\Controllers\Blog\BlogShowController;
use App\Http\Controllers\NewsletterConfirmationController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\NewsletterUnsubscribeController;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $featuredPosts = BlogPost::query()
        ->with('category')
        ->published()
        ->where('is_featured', true)
        ->latest('published_at')
        ->limit(3)
        ->get()
        ->map(fn (BlogPost $post): array => [
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'tag' => $post->category->name,
            'readTime' => ($post->reading_time_minutes ?? 1).' min',
            'accent' => $post->category->accent,
        ])
        ->values();

    $latestPosts = BlogPost::query()
        ->with('category')
        ->published()
        ->latest('published_at')
        ->limit(4)
        ->get()
        ->map(fn (BlogPost $post): array => [
            'slug' => $post->slug,
            'title' => $post->title,
            'date' => $post->published_at->format('M j, Y'),
            'dateTime' => $post->published_at->toDateString(),
            'category' => $post->category->name,
        ])
        ->values();

    return Inertia::render('Welcome', [
        'featuredPosts' => $featuredPosts,
        'latestPosts' => $latestPosts,
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
Route::get('/blog/{slug}', BlogShowController::class)->name('blog.show');

Route::inertia('/legal/privacy', 'Legal/PrivacyPolicy')->name('legal.privacy');
Route::inertia('/legal/terms', 'Legal/TermsOfService')->name('legal.terms');
Route::inertia('/legal/cookies', 'Legal/CookiePolicy')->name('legal.cookies');
