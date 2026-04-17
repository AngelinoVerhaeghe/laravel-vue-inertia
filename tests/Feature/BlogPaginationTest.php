<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Inertia\Testing\AssertableInertia;

test('blog index exposes pagination metadata', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()
        ->count(BlogPost::PUBLIC_PER_PAGE + 3)
        ->for($category, 'category')
        ->published()
        ->create();

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.currentPage', 1)
            ->where('pagination.perPage', BlogPost::PUBLIC_PER_PAGE)
            ->where('pagination.lastPage', 2)
            ->where('pagination.total', BlogPost::PUBLIC_PER_PAGE + 3)
            ->has('pagination.links', 2)
            ->where('pagination.prevUrl', null)
            ->where('pagination.nextUrl', route('blog.index').'?page=2')
            ->has('posts', BlogPost::PUBLIC_PER_PAGE)
        );
});

test('blog index page 2 returns the remaining posts and self-canonicals', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()
        ->count(BlogPost::PUBLIC_PER_PAGE + 3)
        ->for($category, 'category')
        ->published()
        ->create();

    $this->get(route('blog.index').'?page=2')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.currentPage', 2)
            ->where('pagination.lastPage', 2)
            ->has('posts', 3)
            ->where('seo.canonical', route('blog.index').'?page=2')
            ->where('seo.title', fn (string $title) => str_contains($title, 'Page 2'))
        );
});

test('category archive paginates and slices correctly', function () {
    $category = BlogCategory::factory()->state(['slug' => 'frontend'])->create();

    BlogPost::factory()
        ->count(BlogPost::PUBLIC_PER_PAGE + 2)
        ->for($category, 'category')
        ->published()
        ->create();

    $this->get(route('blog.category', ['slug' => 'frontend']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.lastPage', 2)
            ->has('posts', BlogPost::PUBLIC_PER_PAGE)
        );

    $this->get(route('blog.category', ['slug' => 'frontend']).'?page=2')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.currentPage', 2)
            ->has('posts', 2)
            ->where('seo.canonical', route('blog.category', ['slug' => 'frontend']).'?page=2')
        );
});

test('tag archive paginates and slices correctly', function () {
    $category = BlogCategory::factory()->create();
    $tag = BlogTag::factory()->state(['slug' => 'vue'])->create();

    $posts = BlogPost::factory()
        ->count(BlogPost::PUBLIC_PER_PAGE + 1)
        ->for($category, 'category')
        ->published()
        ->create();

    foreach ($posts as $post) {
        $post->tags()->attach($tag);
    }

    $this->get(route('blog.tag', ['slug' => 'vue']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.lastPage', 2)
            ->has('posts', BlogPost::PUBLIC_PER_PAGE)
        );

    $this->get(route('blog.tag', ['slug' => 'vue']).'?page=2')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('pagination.currentPage', 2)
            ->has('posts', 1)
        );
});
