<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Inertia\Testing\AssertableInertia;

test('category archive lists only that category\'s published posts', function () {
    $frontend = BlogCategory::factory()->state(['name' => 'Frontend', 'slug' => 'frontend', 'accent' => 'secondary'])->create();
    $backend = BlogCategory::factory()->state(['name' => 'Backend', 'slug' => 'backend', 'accent' => 'amber'])->create();

    $inCategory = BlogPost::factory()->for($frontend, 'category')->published()->create(['title' => 'Frontend pick']);
    $otherCategory = BlogPost::factory()->for($backend, 'category')->published()->create(['title' => 'Backend pick']);
    $draftInCategory = BlogPost::factory()->for($frontend, 'category')->create(['title' => 'Frontend draft']);

    $this->get(route('blog.category', ['slug' => $frontend->slug]))
        ->assertOk()
        ->assertSee('Frontend pick')
        ->assertDontSee('Backend pick')
        ->assertDontSee('Frontend draft')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Archive')
            ->where('archive.type', 'category')
            ->where('archive.name', 'Frontend')
            ->where('archive.slug', 'frontend')
            ->where('archive.accent', 'secondary')
            ->where('seo.canonical', route('blog.category', ['slug' => 'frontend']))
            ->where('seo.jsonLd.@graph.0.@type', 'CollectionPage')
            ->where('seo.jsonLd.@graph.1.@type', 'ItemList')
            ->where('seo.jsonLd.@graph.2.@type', 'BreadcrumbList')
        );
});

test('unknown category slug returns 404', function () {
    $this->get(route('blog.category', ['slug' => 'does-not-exist']))->assertNotFound();
});

test('tag archive lists only posts attached to that tag and that are published', function () {
    $category = BlogCategory::factory()->create();
    $vue = BlogTag::factory()->state(['name' => 'Vue', 'slug' => 'vue'])->create();
    $rust = BlogTag::factory()->state(['name' => 'Rust', 'slug' => 'rust'])->create();

    $tagged = BlogPost::factory()->for($category, 'category')->published()->create(['title' => 'Vue tagged post']);
    $tagged->tags()->attach($vue);

    $otherTag = BlogPost::factory()->for($category, 'category')->published()->create(['title' => 'Rust tagged post']);
    $otherTag->tags()->attach($rust);

    $draftTagged = BlogPost::factory()->for($category, 'category')->create(['title' => 'Vue draft']);
    $draftTagged->tags()->attach($vue);

    $this->get(route('blog.tag', ['slug' => 'vue']))
        ->assertOk()
        ->assertSee('Vue tagged post')
        ->assertDontSee('Rust tagged post')
        ->assertDontSee('Vue draft')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Archive')
            ->where('archive.type', 'tag')
            ->where('archive.name', 'Vue')
            ->where('archive.slug', 'vue')
            ->where('seo.canonical', route('blog.tag', ['slug' => 'vue']))
        );
});

test('unknown tag slug returns 404', function () {
    $this->get(route('blog.tag', ['slug' => 'does-not-exist']))->assertNotFound();
});

test('empty category archive renders 200 with empty state', function () {
    $category = BlogCategory::factory()->state(['slug' => 'devops', 'name' => 'DevOps'])->create();

    $this->get(route('blog.category', ['slug' => 'devops']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Archive')
            ->where('posts', [])
            ->where('archive.name', 'DevOps')
        );
});

test('noindex posts are excluded from category and tag archives', function () {
    $category = BlogCategory::factory()->state(['slug' => 'frontend'])->create();
    $tag = BlogTag::factory()->state(['slug' => 'vue'])->create();

    $hidden = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'Hidden from search', 'meta_noindex' => true]);

    $hidden->tags()->attach($tag);

    $this->get(route('blog.category', ['slug' => 'frontend']))
        ->assertOk()
        ->assertDontSee('Hidden from search');

    $this->get(route('blog.tag', ['slug' => 'vue']))
        ->assertOk()
        ->assertDontSee('Hidden from search');
});

test('sitemap includes category and tag archives that have published indexable posts', function () {
    $usedCategory = BlogCategory::factory()->state(['slug' => 'frontend'])->create();
    $emptyCategory = BlogCategory::factory()->state(['slug' => 'empty-cat'])->create();
    $usedTag = BlogTag::factory()->state(['slug' => 'vue'])->create();
    $emptyTag = BlogTag::factory()->state(['slug' => 'empty-tag'])->create();

    $post = BlogPost::factory()->for($usedCategory, 'category')->published()->create();
    $post->tags()->attach($usedTag);

    $response = $this->get(route('sitemap'));

    $response->assertOk();
    $response->assertSee(route('blog.category', ['slug' => 'frontend']), false);
    $response->assertSee(route('blog.tag', ['slug' => 'vue']), false);
    $response->assertDontSee(route('blog.category', ['slug' => 'empty-cat']), false);
    $response->assertDontSee(route('blog.tag', ['slug' => 'empty-tag']), false);
});
