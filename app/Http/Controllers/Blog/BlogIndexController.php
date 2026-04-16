<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Inertia\Inertia;
use Inertia\Response;

class BlogIndexController extends Controller
{
    public function __invoke(): Response
    {
        $posts = BlogPost::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->get()
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

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }
}
