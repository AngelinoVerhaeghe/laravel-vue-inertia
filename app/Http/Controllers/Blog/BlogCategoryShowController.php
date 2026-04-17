<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Blog\Concerns\BuildsPaginationPayload;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Inertia\Inertia;
use Inertia\Response;

class BlogCategoryShowController extends Controller
{
    use BuildsPaginationPayload;

    public function __invoke(string $slug): Response
    {
        $category = BlogCategory::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $paginator = $category
            ->posts()
            ->with('category')
            ->published()
            ->where('meta_noindex', false)
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

        $baseUrl = route('blog.category', ['slug' => $category->slug]);
        $canonical = $paginator->currentPage() > 1
            ? $paginator->url($paginator->currentPage())
            : $baseUrl;

        $description = "Articles in the {$category->name} category from ".config('seo.site_name').'.';
        $title = $category->name.' — Blog — '.config('seo.site_name');

        if ($paginator->currentPage() > 1) {
            $title = $category->name.' (Page '.$paginator->currentPage().') — Blog — '.config('seo.site_name');
        }

        $seo = SeoPayload::make([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'website',
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'CollectionPage',
                        'name' => $category->name,
                        'description' => $description,
                        'url' => $baseUrl,
                        'isPartOf' => [
                            '@type' => 'Blog',
                            'name' => (string) config('seo.site_name'),
                            'url' => route('blog.index'),
                        ],
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
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $category->name, 'item' => $baseUrl],
                        ],
                    ],
                ],
            ],
        ]);

        return Inertia::render('Blog/Archive', [
            'archive' => [
                'type' => 'category',
                'name' => $category->name,
                'slug' => $category->slug,
                'accent' => $category->accent,
            ],
            'posts' => $posts,
            'pagination' => $this->paginationPayload($paginator),
            'seo' => $seo,
        ]);
    }
}
