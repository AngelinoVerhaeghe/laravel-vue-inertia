<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogShowController extends Controller
{
    public function __invoke(string $slug): Response
    {
        $post = BlogPost::query()
            ->with(['category', 'tags', 'featuredImage', 'metaOgImage'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $featuredImageUrl = $post->featuredImage
            ? $post->featuredImage->url()
            : ($post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : null);

        $seo = $this->buildSeoPayload($post, $featuredImageUrl);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Blog', 'url' => route('blog.index')],
            ['name' => $post->title, 'url' => route('blog.show', ['slug' => $post->slug])],
        ];

        $related = $this->buildRelatedPayload($post);

        return Inertia::render('Blog/Show', [
            'post' => [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name,
                'categorySlug' => $post->category->slug,
                'date' => $post->published_at->format('M j, Y'),
                'dateTime' => $post->published_at->toDateString(),
                'readTime' => ($post->reading_time_minutes ?? 1).' min',
                'accent' => $post->category->accent,
                'featuredImageUrl' => $featuredImageUrl,
                'tags' => $post->tags->map(fn ($tag): array => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])->values(),
                'bodyHtml' => $post->body_html ?? '',
            ],
            'breadcrumbs' => $breadcrumbs,
            'related' => $related,
            'seo' => $seo,
        ]);
    }

    /**
     * @return array{category: ?array<string, mixed>, tags: ?array<string, mixed>}
     */
    private function buildRelatedPayload(BlogPost $post): array
    {
        $moreInCategory = BlogPost::query()
            ->with('category')
            ->published()
            ->where('meta_noindex', false)
            ->where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $tagIds = $post->tags->pluck('id');

        $taggedWith = $tagIds->isEmpty()
            ? collect()
            : BlogPost::query()
                ->with('category')
                ->published()
                ->where('meta_noindex', false)
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $moreInCategory->pluck('id'))
                ->whereHas('tags', fn ($query) => $query->whereIn('blog_tags.id', $tagIds))
                ->latest('published_at')
                ->limit(3)
                ->get();

        return [
            'category' => $moreInCategory->isNotEmpty() ? [
                'name' => $post->category->name,
                'slug' => $post->category->slug,
                'posts' => $moreInCategory->map(fn (BlogPost $related): array => $this->toCardPayload($related))->values(),
            ] : null,
            'tags' => $taggedWith->isNotEmpty() ? [
                'names' => $post->tags->pluck('name')->values(),
                'posts' => $taggedWith->map(fn (BlogPost $related): array => $this->toCardPayload($related))->values(),
            ] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function toCardPayload(BlogPost $post): array
    {
        return [
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'category' => $post->category->name,
            'categorySlug' => $post->category->slug,
            'date' => $post->published_at->format('M j, Y'),
            'dateTime' => $post->published_at->toDateString(),
            'readTime' => ($post->reading_time_minutes ?? 1).' min',
            'accent' => $post->category->accent,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSeoPayload(BlogPost $post, ?string $featuredImageUrl): array
    {
        $ogImage = $post->metaOgImage
            ? $post->metaOgImage->url()
            : $featuredImageUrl;

        $description = $post->meta_description ?: Str::limit(strip_tags($post->excerpt), 200);
        $title = $post->meta_title ?: $post->title.' — '.config('seo.site_name');
        $canonical = route('blog.show', ['slug' => $post->slug]);

        return SeoPayload::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'image' => $ogImage,
            'imageAlt' => $post->title,
            'type' => 'article',
            'noindex' => (bool) $post->meta_noindex,
            'article' => [
                'publishedTime' => $post->published_at?->toIso8601String(),
                'modifiedTime' => $post->updated_at?->toIso8601String(),
                'section' => $post->category->name,
                'tags' => $post->tags->pluck('name')->all(),
            ],
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Article',
                        'headline' => $post->title,
                        'description' => $description,
                        'datePublished' => $post->published_at?->toIso8601String(),
                        'dateModified' => $post->updated_at?->toIso8601String(),
                        'mainEntityOfPage' => [
                            '@type' => 'WebPage',
                            '@id' => $canonical,
                        ],
                        'image' => $ogImage ? [$ogImage] : [],
                        'articleSection' => $post->category->name,
                        'keywords' => $post->tags->pluck('name')->all(),
                        'publisher' => SeoPayload::organization(),
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => $canonical],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
