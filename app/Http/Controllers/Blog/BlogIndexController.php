<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Inertia\Inertia;
use Inertia\Response;

class BlogIndexController extends Controller
{
    public function __invoke(): Response
    {
        $postModels = BlogPost::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->get();

        $posts = $postModels
            ->map(fn (BlogPost $post): array => [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name,
                'date' => $post->published_at->format('M j, Y'),
                'dateTime' => $post->published_at->toDateString(),
                'readTime' => ($post->reading_time_minutes ?? 1).' min',
                'accent' => $post->category->accent,
            ])
            ->values();

        $itemListElements = $postModels
            ->values()
            ->map(fn (BlogPost $post, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('blog.show', ['slug' => $post->slug]),
                'name' => $post->title,
            ])
            ->all();

        $seo = SeoPayload::make([
            'title' => 'Blog — '.config('seo.site_name'),
            'description' => 'Browse articles from Stack Notes on frontend, APIs, databases, DevOps, and full-stack craft.',
            'canonical' => route('blog.index'),
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
                        'numberOfItems' => $postModels->count(),
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
            'seo' => $seo,
        ]);
    }
}
