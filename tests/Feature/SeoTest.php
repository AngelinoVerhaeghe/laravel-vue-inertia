<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Inertia\Testing\AssertableInertia;

test('shared seo defaults are available to every inertia page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('seoDefaults.title')
            ->has('seoDefaults.description')
            ->has('seoDefaults.siteName')
            ->has('seoDefaults.siteUrl')
            ->has('seoDefaults.twitterCard')
        );
});

test('home page exposes an seo payload with canonical and jsonLd', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.canonical', route('home'))
            ->where('seo.type', 'website')
            ->where('seo.jsonLd.@graph.0.@type', 'WebSite')
            ->where('seo.jsonLd.@graph.1.@type', 'Organization')
        );
});

test('blog show exposes article seo payload with overrides', function () {
    $category = BlogCategory::factory()->state(['name' => 'Frontend', 'accent' => 'secondary'])->create();
    $tag = BlogTag::factory()->state(['name' => 'Vue'])->create();

    $post = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create([
            'title' => 'Deep dive into Vue reactivity',
            'excerpt' => 'How ref, reactive, and computed tick under the hood.',
            'meta_title' => 'Vue reactivity — Stack Notes',
            'meta_description' => 'A focused tour of Vue 3 reactivity primitives.',
        ]);

    $post->tags()->attach($tag);

    $this->get(route('blog.show', ['slug' => $post->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.title', 'Vue reactivity — Stack Notes')
            ->where('seo.description', 'A focused tour of Vue 3 reactivity primitives.')
            ->where('seo.type', 'article')
            ->where('seo.canonical', route('blog.show', ['slug' => $post->slug]))
            ->where('seo.jsonLd.@graph.0.@type', 'Article')
            ->where('seo.jsonLd.@graph.0.headline', 'Deep dive into Vue reactivity')
            ->where('seo.jsonLd.@graph.1.@type', 'BreadcrumbList')
            ->where('seo.article.section', 'Frontend')
            ->has('breadcrumbs', 3)
            ->where('breadcrumbs.2.name', 'Deep dive into Vue reactivity')
        );
});

test('blog show falls back to title and excerpt when overrides are empty', function () {
    $category = BlogCategory::factory()->create();

    $post = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create([
            'title' => 'Auto meta from title',
            'excerpt' => 'Auto meta description from excerpt.',
            'meta_title' => null,
            'meta_description' => null,
        ]);

    $this->get(route('blog.show', ['slug' => $post->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.description', 'Auto meta description from excerpt.')
            ->where(
                'seo.title',
                'Auto meta from title — '.config('seo.site_name'),
            )
        );
});

test('blog show marks noindex posts accordingly', function () {
    $category = BlogCategory::factory()->create();

    $post = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['meta_noindex' => true]);

    $this->get(route('blog.show', ['slug' => $post->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.noindex', true)
        );
});

test('sitemap lists static pages and published, indexable posts', function () {
    $category = BlogCategory::factory()->create();

    $published = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['slug' => 'hello-world']);

    $noindex = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['slug' => 'hidden-from-search', 'meta_noindex' => true]);

    $draft = BlogPost::factory()
        ->for($category, 'category')
        ->create(['slug' => 'draft-post']);

    $response = $this->get(route('sitemap'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $response->assertSee(route('home'), false);
    $response->assertSee(route('blog.index'), false);
    $response->assertSee(route('blog.show', ['slug' => $published->slug]), false);
    $response->assertDontSee(route('blog.show', ['slug' => $noindex->slug]), false);
    $response->assertDontSee(route('blog.show', ['slug' => $draft->slug]), false);
});

test('blog index exposes an ItemList and BreadcrumbList', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'First public post']);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.canonical', route('blog.index'))
            ->where('seo.jsonLd.@graph.0.@type', 'Blog')
            ->where('seo.jsonLd.@graph.1.@type', 'ItemList')
            ->where('seo.jsonLd.@graph.1.itemListElement.0.name', 'First public post')
            ->where('seo.jsonLd.@graph.2.@type', 'BreadcrumbList')
        );
});

test('robots.txt disallows dashboard and points at sitemap', function () {
    $response = $this->get(route('robots'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    $response->assertSee('User-agent: *');
    $response->assertSee('Disallow: /dashboard');
    $response->assertSee('Sitemap: '.route('sitemap'));
});
