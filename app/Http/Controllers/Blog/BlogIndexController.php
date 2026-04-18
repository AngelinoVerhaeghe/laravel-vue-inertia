<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Blog\Concerns\BuildsPaginationPayload;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Inertia\Inertia;
use Inertia\Response;

class BlogIndexController extends Controller
{
    use BuildsPaginationPayload;

    public function __invoke(): Response
    {
        $paginator = BlogPost::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->paginate(BlogPost::PUBLIC_PER_PAGE)
            ->withQueryString();

        $posts = collect($paginator->items())
            ->map(fn (BlogPost $post): array => [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name,
                'categorySlug' => $post->category->slug,
                'date' => $post->published_at->format('M j, Y'),
                'dateTime' => $post->published_at->toDateString(),
                'readTime' => ($post->reading_time_minutes ?? 1).' min',
                'accent' => $post->category->accent,
            ])
            ->values();

        $itemListElements = collect($paginator->items())
            ->values()
            ->map(fn (BlogPost $post, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('blog.show', ['slug' => $post->slug]),
                'name' => $post->title,
            ])
            ->all();

        $canonical = $paginator->currentPage() > 1
            ? $paginator->url($paginator->currentPage())
            : route('blog.index');

        $title = $paginator->currentPage() > 1
            ? 'Blog (Page '.$paginator->currentPage().') — '.config('seo.site_name')
            : 'Blog — '.config('seo.site_name');

        $seo = SeoPayload::make([
            'title' => $title,
            'description' => 'Tutorials, deep dives, and field notes on full-stack web development — Vue 3, Laravel APIs, PostgreSQL, Redis, Docker, performance, and accessibility — written from real production work.',
            'canonical' => $canonical,
            'type' => 'website',
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Blog',
                        'name' => (string) config('seo.site_name'),
                        'url' => route('blog.index'),
                        'description' => 'Field notes from the stack: frontend, APIs, databases, and DevOps.',
                    ],
                    [
                        '@type' => 'ItemList',
                        'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
                        'numberOfItems' => $paginator->total(),
                        'itemListElement' => $itemListElements,
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
                        ],
                    ],
                ],
            ],
        ]);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'pagination' => $this->paginationPayload($paginator),
            'seo' => $seo,
        ]);
    }
}
