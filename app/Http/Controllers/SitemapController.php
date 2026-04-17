<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticUrls = collect([
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('blog.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('newsletter'), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('legal.privacy'), 'changefreq' => 'yearly', 'priority' => '0.2'],
            ['loc' => route('legal.terms'), 'changefreq' => 'yearly', 'priority' => '0.2'],
            ['loc' => route('legal.cookies'), 'changefreq' => 'yearly', 'priority' => '0.2'],
        ]);

        $postUrls = BlogPost::query()
            ->published()
            ->where('meta_noindex', false)
            ->latest('published_at')
            ->get()
            ->map(fn (BlogPost $post): array => [
                'loc' => route('blog.show', ['slug' => $post->slug]),
                'lastmod' => $post->updated_at?->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);

        $categoryUrls = BlogCategory::query()
            ->whereHas('posts', fn ($query) => $query->published()->where('meta_noindex', false))
            ->orderBy('name')
            ->get()
            ->map(fn (BlogCategory $category): array => [
                'loc' => route('blog.category', ['slug' => $category->slug]),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ]);

        $tagUrls = BlogTag::query()
            ->whereHas('posts', fn ($query) => $query->published()->where('meta_noindex', false))
            ->orderBy('name')
            ->get()
            ->map(fn (BlogTag $tag): array => [
                'loc' => route('blog.tag', ['slug' => $tag->slug]),
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ]);

        $urls = $staticUrls
            ->concat($postUrls)
            ->concat($categoryUrls)
            ->concat($tagUrls);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars((string) ($entry['loc'] ?? ''), ENT_XML1).'</loc>'."\n";

            if (! empty($entry['lastmod'])) {
                $xml .= '    <lastmod>'.htmlspecialchars((string) $entry['lastmod'], ENT_XML1).'</lastmod>'."\n";
            }

            $xml .= '    <changefreq>'.htmlspecialchars((string) ($entry['changefreq'] ?? 'monthly'), ENT_XML1).'</changefreq>'."\n";
            $xml .= '    <priority>'.htmlspecialchars((string) ($entry['priority'] ?? '0.5'), ENT_XML1).'</priority>'."\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>'."\n";

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
