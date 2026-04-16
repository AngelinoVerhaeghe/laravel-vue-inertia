<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site identity
    |--------------------------------------------------------------------------
    |
    | These values are used as SEO/OpenGraph/Twitter defaults across the
    | marketing site when a page does not provide its own overrides.
    |
    */

    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'Stack Notes')),

    'site_url' => env('SEO_SITE_URL', env('APP_URL', 'http://localhost')),

    'default_title' => env('SEO_DEFAULT_TITLE', 'Stack Notes — tech, web & full-stack'),

    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Field notes from the stack: frontend craft, API design, databases, and the glue that holds production apps together.'
    ),

    'default_image' => env('SEO_DEFAULT_IMAGE', '/images/stack-notes-footer-logo.png'),

    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@stacknotes'),

    'locale' => env('SEO_LOCALE', 'en_US'),

    'publisher_logo' => env('SEO_PUBLISHER_LOGO', '/images/stack-notes-footer-logo.png'),

    /*
    |--------------------------------------------------------------------------
    | Social / "sameAs" URLs
    |--------------------------------------------------------------------------
    |
    | Used by the Organization JSON-LD on the home page so search engines can
    | connect the site to its official social profiles. Leave empty to skip.
    |
    */

    'social' => array_values(array_filter([
        env('SEO_SOCIAL_GITHUB'),
        env('SEO_SOCIAL_X'),
        env('SEO_SOCIAL_LINKEDIN'),
        env('SEO_SOCIAL_YOUTUBE'),
        env('SEO_SOCIAL_DISCORD'),
    ])),
];
