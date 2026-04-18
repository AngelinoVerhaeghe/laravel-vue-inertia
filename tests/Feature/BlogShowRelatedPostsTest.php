<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Inertia\Testing\AssertableInertia;

test('exposes more in category rail with newest siblings excluding current post', function () {
    $category = BlogCategory::factory()->state(['name' => 'Frontend', 'slug' => 'frontend'])->create();

    $current = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'Current post', 'published_at' => now()->subDay()]);

    $older = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'Older sibling', 'published_at' => now()->subDays(5)]);

    $newer = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'Newer sibling', 'published_at' => now()->subHour()]);

    $this->get(route('blog.show', ['slug' => $current->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Show')
            ->where('related.category.name', 'Frontend')
            ->where('related.category.slug', 'frontend')
            ->has('related.category.posts', 2)
            ->where('related.category.posts.0.slug', $newer->slug)
            ->where('related.category.posts.1.slug', $older->slug)
            ->where('related.tags', null)
        );
});

test('caps more in category rail at three posts', function () {
    $category = BlogCategory::factory()->create();
    $current = BlogPost::factory()->for($category, 'category')->published()->create();

    BlogPost::factory()
        ->count(5)
        ->for($category, 'category')
        ->published()
        ->create();

    $this->get(route('blog.show', ['slug' => $current->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Show')
            ->has('related.category.posts', 3)
        );
});

test('exposes tagged with rail deduped against category rail', function () {
    $frontend = BlogCategory::factory()->state(['name' => 'Frontend'])->create();
    $backend = BlogCategory::factory()->state(['name' => 'Backend'])->create();

    $vue = BlogTag::factory()->state(['name' => 'Vue', 'slug' => 'vue'])->create();

    $current = BlogPost::factory()->for($frontend, 'category')->published()->create();
    $current->tags()->attach($vue);

    $sameCategoryAndTag = BlogPost::factory()
        ->for($frontend, 'category')
        ->published()
        ->create(['title' => 'Same category and tag']);
    $sameCategoryAndTag->tags()->attach($vue);

    $sharedTagOtherCategory = BlogPost::factory()
        ->for($backend, 'category')
        ->published()
        ->create(['title' => 'Other category shared tag']);
    $sharedTagOtherCategory->tags()->attach($vue);

    $this->get(route('blog.show', ['slug' => $current->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Show')
            ->has('related.category.posts', 1)
            ->where('related.category.posts.0.slug', $sameCategoryAndTag->slug)
            ->has('related.tags.posts', 1)
            ->where('related.tags.posts.0.slug', $sharedTagOtherCategory->slug)
            ->where('related.tags.names.0', 'Vue')
        );
});

test('omits both rails when post is alone in category and has no tags', function () {
    $category = BlogCategory::factory()->create();
    $current = BlogPost::factory()->for($category, 'category')->published()->create();

    $this->get(route('blog.show', ['slug' => $current->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Show')
            ->where('related.category', null)
            ->where('related.tags', null)
        );
});

test('excludes noindex and unpublished posts from both rails', function () {
    $category = BlogCategory::factory()->create();
    $tag = BlogTag::factory()->create();

    $current = BlogPost::factory()->for($category, 'category')->published()->create();
    $current->tags()->attach($tag);

    $hiddenSameCategory = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create(['title' => 'Hidden sibling', 'meta_noindex' => true]);
    $hiddenSameCategory->tags()->attach($tag);

    $draftSameCategory = BlogPost::factory()
        ->for($category, 'category')
        ->create(['title' => 'Draft sibling']);
    $draftSameCategory->tags()->attach($tag);

    $otherCategory = BlogCategory::factory()->create();
    $hiddenSharedTag = BlogPost::factory()
        ->for($otherCategory, 'category')
        ->published()
        ->create(['title' => 'Hidden tagged', 'meta_noindex' => true]);
    $hiddenSharedTag->tags()->attach($tag);

    $this->get(route('blog.show', ['slug' => $current->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Show')
            ->where('related.category', null)
            ->where('related.tags', null)
        );
});
