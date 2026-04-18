<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Inertia\Testing\AssertableInertia;

test('q filters by title', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Vue patterns for shipping faster',
        'excerpt' => 'Components, composables, and state.',
    ]);

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Laravel queue strategies',
        'excerpt' => 'Workers, retries, and timeouts.',
    ]);

    $this->get(route('blog.index').'?q=vue')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('query', 'vue')
            ->has('posts', 1)
            ->where('posts.0.title', 'Vue patterns for shipping faster')
        );
});

test('q filters by excerpt', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Frontend notes',
        'excerpt' => 'Reactive composables in modern apps.',
        'body_markdown' => 'Plain prose only.',
    ]);

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Backend notes',
        'excerpt' => 'API endpoints and validation rules.',
        'body_markdown' => 'Plain prose only.',
    ]);

    $this->get(route('blog.index').'?q=composables')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('posts', 1)
            ->where('posts.0.title', 'Frontend notes')
        );
});

test('q filters by body markdown', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Generic title one',
        'excerpt' => 'Generic excerpt one.',
        'body_markdown' => 'Detailed walkthrough of unicornicorn behavior.',
    ]);

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Generic title two',
        'excerpt' => 'Generic excerpt two.',
        'body_markdown' => 'Talks about kittens and rainbows.',
    ]);

    $this->get(route('blog.index').'?q=unicornicorn')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('posts', 1)
            ->where('posts.0.title', 'Generic title one')
        );
});

test('q is case insensitive', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Vue patterns for shipping faster',
    ]);

    $this->get(route('blog.index').'?q=VUE')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('posts', 1)
            ->where('posts.0.title', 'Vue patterns for shipping faster')
        );
});

test('empty q returns the unfiltered listing', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->count(3)->for($category, 'category')->published()->create();

    $this->get(route('blog.index').'?q=')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('query', '')
            ->has('posts', 3)
            ->where('seo.noindex', false)
        );
});

test('q is preserved across pagination links', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()
        ->count(BlogPost::PUBLIC_PER_PAGE + 2)
        ->for($category, 'category')
        ->published()
        ->create([
            'title' => 'Demo post about something',
        ]);

    $this->get(route('blog.index').'?q=demo')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('query', 'demo')
            ->where('pagination.lastPage', 2)
            ->where('pagination.nextUrl', fn (string $url) => str_contains($url, 'q=demo')
                && str_contains($url, 'page=2')
            )
        );
});

test('search results page sets noindex and search-aware title in seo', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Demo post',
    ]);

    $this->get(route('blog.index').'?q=demo')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('seo.noindex', true)
            ->where('seo.title', fn (string $title) => str_contains($title, 'Search: "demo"'))
            ->where('seo.canonical', fn (string $canonical) => str_contains($canonical, 'q=demo'))
        );
});

test('q is trimmed of surrounding whitespace', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Demo post',
    ]);

    $this->get(route('blog.index').'?q='.urlencode('  demo  '))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('query', 'demo')
            ->has('posts', 1)
        );
});

test('q is length capped at 100 characters', function () {
    $category = BlogCategory::factory()->create();

    BlogPost::factory()->for($category, 'category')->published()->create();

    $oversized = str_repeat('a', 200);

    $this->get(route('blog.index').'?q='.$oversized)
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('query', fn (string $query) => strlen($query) === 100)
        );
});
