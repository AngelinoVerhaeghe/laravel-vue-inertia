<?php

namespace App\Support;

/**
 * Sample blog content for the marketing site until a real model exists.
 *
 * @phpstan-type PostSummary array{
 *     slug: string,
 *     title: string,
 *     excerpt: string,
 *     category: string,
 *     date: string,
 *     dateTime: string,
 *     readTime: string,
 *     accent: string
 * }
 * @phpstan-type Post array{
 *     slug: string,
 *     title: string,
 *     excerpt: string,
 *     category: string,
 *     date: string,
 *     dateTime: string,
 *     readTime: string,
 *     accent: string,
 *     body: list<string>
 * }
 */
final class BlogSamplePosts
{
    /**
     * @return list<PostSummary>
     */
    public static function summaries(): array
    {
        return array_map(fn (array $post): array => [
            'slug' => $post['slug'],
            'title' => $post['title'],
            'excerpt' => $post['excerpt'],
            'category' => $post['category'],
            'date' => $post['date'],
            'dateTime' => $post['dateTime'],
            'readTime' => $post['readTime'],
            'accent' => $post['accent'],
        ], self::all());
    }

    /**
     * @return Post|null
     */
    public static function find(string $slug): ?array
    {
        foreach (self::all() as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @return list<Post>
     */
    private static function all(): array
    {
        return [
            [
                'slug' => 'monolith-to-modular-apis',
                'title' => 'From monolith to modular APIs—without the drama',
                'excerpt' => 'How to line up endpoints step by step, keep versioning sane, and keep your team grounded during the transition.',
                'category' => 'Backend',
                'date' => 'Mar 12, 2026',
                'dateTime' => '2026-03-12',
                'readTime' => '9 min',
                'accent' => 'amber',
                'body' => [
                    'Breaking a monolith apart is less about frameworks and more about contracts. When every team agrees what “stable” means for an API surface, you stop shipping accidental breaking changes every sprint.',
                    'Start by inventorying consumers: who calls what, how often, and what failure modes they tolerate. Versioning is not vanity—it is a promise. Prefix paths or headers both work; pick one, document it, and enforce it in CI where you can.',
                    'Roll out strangler-style: carve vertical slices, proxy at the edge, and delete dead paths only when traffic graphs say zero. The goal is boring migrations on Tuesdays, not heroics at midnight.',
                ],
            ],
            [
                'slug' => 'inertia-vue-spa-feel',
                'title' => 'Inertia + Vue 3: SPA feel, server-side confidence',
                'excerpt' => 'Why hybrid apps are the sweet spot for many product teams—and patterns you can steal today.',
                'category' => 'Web',
                'date' => 'Mar 8, 2026',
                'dateTime' => '2026-03-08',
                'readTime' => '7 min',
                'accent' => 'primary',
                'body' => [
                    'Inertia lets you keep Laravel’s routing, validation, and auth while still authoring Vue pages that feel like a client-rendered app. You trade a separate JSON API for shared session semantics and fewer moving parts.',
                    'Treat each page as a boundary: load the props you need, keep payloads lean, and prefer lazy data when a screen has heavy secondary panels. Wayfinder (or Ziggy) keeps URLs honest between PHP and TypeScript.',
                    'When you need real-time or offline, augment with a small API slice—but default to server-driven navigation until product requirements truly demand the complexity.',
                ],
            ],
            [
                'slug' => 'css-design-tokens',
                'title' => 'CSS that scales: design tokens in the real world',
                'excerpt' => 'From Figma to Tailwind: naming, themes, and when you actually need a component library.',
                'category' => 'Frontend',
                'date' => 'Mar 1, 2026',
                'dateTime' => '2026-03-01',
                'readTime' => '6 min',
                'accent' => 'secondary',
                'body' => [
                    'Tokens are the vocabulary between design and engineering. If designers name colors “surface/raised” and developers hard-code hex values in three places, everyone loses.',
                    'Tailwind v4’s @theme layer is a pragmatic home for those tokens: one source for fonts, radii, and semantic colors. Pair it with a short README so contributors know which utility maps to which token.',
                    'Component libraries pay off when the same primitives appear in dozens of screens. Until then, compose with utility-first patterns and extract only after repetition hurts.',
                ],
            ],
            [
                'slug' => 'docker-compose-sanity',
                'title' => 'Docker Compose for local full-stack: a sanity checklist',
                'excerpt' => 'Ports, volumes, and the tiny details that keep new hires shipping on day one.',
                'category' => 'DevOps',
                'date' => 'Feb 22, 2026',
                'dateTime' => '2026-02-22',
                'readTime' => '5 min',
                'accent' => 'amber',
                'body' => [
                    'Local parity matters: same PHP extensions, same Node major, same database version your CI uses. Bake that into Compose and document the three commands that matter: up, down, logs.',
                    'Mount the project directory read-write for hot reload, but never let node_modules fight the host—use named volumes when teams mix macOS and Linux.',
                    'Healthchecks on backing services save hours of “it works on my machine.” Fail fast when MySQL is not ready instead of chasing connection errors in Laravel logs.',
                ],
            ],
        ];
    }
}
