<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Blog\Concerns\BuildsPaginationPayload;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Support\Seo\SeoPayload;
use Inertia\Inertia;
use Inertia\Response;

class BlogTagShowController extends Controller
{
    use BuildsPaginationPayload;

    public function __invoke(string $slug): Response
    {
        $tag = BlogTag::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $paginator = $tag
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

        $baseUrl = route('blog.tag', ['slug' => $tag->slug]);
        $canonical = $paginator->currentPage() > 1
            ? $paginator->url($paginator->currentPage())
            : $baseUrl;

        $description = "Articles tagged {$tag->name} from ".config('seo.site_name').'.';
        $title = '#'.$tag->name.' — Blog — '.config('seo.site_name');

        if ($paginator->currentPage() > 1) {
            $title = '#'.$tag->name.' (Page '.$paginator->currentPage().') — Blog — '.config('seo.site_name');
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
                        'name' => '#'.$tag->name,
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
                            ['@type' => 'ListItem', 'position' => 3, 'name' => '#'.$tag->name, 'item' => $baseUrl],
                        ],
                    ],
                ],
            ],
        ]);

        return Inertia::render('Blog/Archive', [
            'archive' => [
                'type' => 'tag',
                'name' => $tag->name,
                'slug' => $tag->slug,
            ],
            'posts' => $posts,
            'pagination' => $this->paginationPayload($paginator),
            'seo' => $seo,
        ]);
    }
}
