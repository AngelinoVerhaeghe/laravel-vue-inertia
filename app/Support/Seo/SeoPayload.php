<?php

namespace App\Support\Seo;

use Illuminate\Support\Str;

class SeoPayload
{
    /**
     * Build a per-page SEO payload merged with site-wide defaults.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function make(array $overrides = []): array
    {
        $siteName = (string) config('seo.site_name');
        $siteUrl = rtrim((string) config('seo.site_url'), '/');

        $title = $overrides['title'] ?? config('seo.default_title');
        $description = $overrides['description'] ?? config('seo.default_description');
        $image = $overrides['image'] ?? config('seo.default_image');
        $canonical = $overrides['canonical'] ?? null;

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => self::absoluteUrl($canonical, $siteUrl),
            'image' => self::absoluteUrl($image, $siteUrl),
            'imageAlt' => $overrides['imageAlt'] ?? $title,
            'type' => $overrides['type'] ?? 'website',
            'siteName' => $siteName,
            'siteUrl' => $siteUrl,
            'locale' => (string) config('seo.locale'),
            'twitterHandle' => (string) config('seo.twitter_handle'),
            'twitterCard' => $overrides['twitterCard'] ?? 'summary_large_image',
            'noindex' => (bool) ($overrides['noindex'] ?? false),
            'article' => $overrides['article'] ?? null,
            'jsonLd' => $overrides['jsonLd'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return self::make();
    }

    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $organization = [
            '@type' => 'Organization',
            'name' => (string) config('seo.site_name'),
            'url' => self::absoluteUrl('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => self::absoluteUrl((string) config('seo.publisher_logo')),
            ],
        ];

        $sameAs = array_values(array_filter((array) config('seo.social', [])));

        if ($sameAs !== []) {
            $organization['sameAs'] = $sameAs;
        }

        return $organization;
    }

    public static function absoluteUrl(?string $value, ?string $siteUrl = null): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        $base = rtrim($siteUrl ?? (string) config('seo.site_url'), '/');

        return $base.'/'.ltrim($value, '/');
    }
}
