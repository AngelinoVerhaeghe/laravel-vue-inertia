<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BlogShowController extends Controller
{
    public function __invoke(string $slug): Response
    {
        $post = BlogPost::query()
            ->with(['category', 'tags', 'featuredImage'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $featuredImageUrl = $post->featuredImage
            ? $post->featuredImage->url()
            : ($post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : null);

        return Inertia::render('Blog/Show', [
            'post' => [
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'category' => $post->category->name,
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
        ]);
    }
}
