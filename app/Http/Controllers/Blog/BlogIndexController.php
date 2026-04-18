<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Blog\Concerns\BuildsPaginationPayload;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\Seo\SeoPayload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class BlogIndexController extends Controller
{
    use BuildsPaginationPayload;

    public function __invoke(Request $request): Response
    {
        $query = $request->string('q')->trim()->limit(100, '')->toString();

        $paginator = BlogPost::query()
            ->with('category')
            ->published()
            ->when($query !== '', function (Builder $builder) use ($query): void {
                $builder->where(function (Builder $where) use ($query): void {
                    $where->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('body_markdown', 'like', "%{$query}%");
                });
            })
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

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'pagination' => $this->paginationPayload($paginator),
            'query' => $query,
            'seo' => $this->buildSeoPayload($paginator, $query),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSeoPayload(LengthAwarePaginator $paginator, string $query): array
    {
        $siteName = (string) config('seo.site_name');
        $page = $paginator->currentPage();
        $isSearch = $query !== '';

        if ($isSearch) {
            $title = $page > 1
                ? sprintf('Search: "%s" (Page %d) — %s', $query, $page, $siteName)
                : sprintf('Search: "%s" — %s', $query, $siteName);

            $description = sprintf(
                'Posts matching "%s" — tutorials, deep dives, and field notes from %s.',
                $query,
                $siteName,
            );

            $canonical = $page > 1
                ? $paginator->url($page)
                : route('blog.index').'?'.http_build_query(['q' => $query]);

            return SeoPayload::make([
                'title' => $title,
                'description' => $description,
                'canonical' => $canonical,
                'type' => 'website',
                'noindex' => true,
                'jsonLd' => [
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'BreadcrumbList',
                            'itemListElement' => [
                                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
                                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Search', 'item' => $canonical],
                            ],
                        ],
                    ],
                ],
            ]);
        }

        $itemListElements = collect($paginator->items())
            ->values()
            ->map(fn (BlogPost $post, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('blog.show', ['slug' => $post->slug]),
                'name' => $post->title,
            ])
            ->all();

        $canonical = $page > 1
            ? $paginator->url($page)
            : route('blog.index');

        $title = $page > 1
            ? 'Blog (Page '.$page.') — '.$siteName
            : 'Blog — '.$siteName;

        return SeoPayload::make([
            'title' => $title,
            'description' => 'Tutorials, deep dives, and field notes on full-stack web development — Vue 3, Laravel APIs, PostgreSQL, Redis, Docker, performance, and accessibility — written from real production work.',
            'canonical' => $canonical,
            'type' => 'website',
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Blog',
                        'name' => $siteName,
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
    }
}
