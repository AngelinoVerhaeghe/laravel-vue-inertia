<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Inertia\Testing\AssertableInertia;

test('blog index lists only published posts', function () {
    $published = BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'primary', 'name' => 'Web']), 'category')
        ->published()
        ->create(['title' => 'Published post']);

    BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'amber']), 'category')
        ->create(['title' => 'Draft post']);

    BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'secondary']), 'category')
        ->scheduled()
        ->create(['title' => 'Scheduled post']);

    $this->get(route('blog.index'))
        ->assertOk()
        ->assertSee('Published post')
        ->assertDontSee('Draft post')
        ->assertDontSee('Scheduled post');
});

test('blog show returns not found for drafts and scheduled posts', function () {
    $draft = BlogPost::factory()->for(BlogCategory::factory(), 'category')->create();
    $scheduled = BlogPost::factory()->for(BlogCategory::factory(), 'category')->scheduled()->create();

    $this->get(route('blog.show', ['slug' => $draft->slug]))->assertNotFound();
    $this->get(route('blog.show', ['slug' => $scheduled->slug]))->assertNotFound();
});

test('blog show renders a published post', function () {
    $post = BlogPost::factory()
        ->for(BlogCategory::factory()->state(['name' => 'Backend', 'accent' => 'amber']), 'category')
        ->published()
        ->create([
            'title' => 'Hello Markdown',
            'body_markdown' => "# Heading\n\nSome text.\n\n```php\necho 'hi';\n```",
        ]);

    $this->get(route('blog.show', ['slug' => $post->slug]))
        ->assertOk()
        ->assertSee('Hello Markdown')
        ->assertSee('Heading')
        ->assertSee("echo 'hi';");
});

test('homepage shows featured posts', function () {
    $featured = BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'primary', 'name' => 'Web']), 'category')
        ->published()
        ->create([
            'title' => 'Featured post',
            'is_featured' => true,
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Featured post')
        ->assertDontSee('No featured posts yet');
});

test('homepage shows pinned headline post', function () {
    $headline = BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'primary', 'name' => 'Backend']), 'category')
        ->published()
        ->headline()
        ->create([
            'title' => 'Pinned headline post',
            'excerpt' => 'A specially curated lead story.',
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Pinned headline post')
        ->assertSee('A specially curated lead story.')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('headlinePost.slug', $headline->slug)
            ->where('headlinePost.title', 'Pinned headline post')
            ->where('headlinePost.category', 'Backend')
        );
});

test('homepage falls back to latest published post when none pinned', function () {
    $category = BlogCategory::factory()->state(['accent' => 'primary'])->create();

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Older post',
        'published_at' => now()->subDays(5),
    ]);

    $latest = BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Most recent post',
        'published_at' => now()->subHour(),
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('headlinePost.slug', $latest->slug)
            ->where('headlinePost.title', 'Most recent post')
        );
});

test('homepage hides headline section when there are no published posts', function () {
    BlogPost::factory()
        ->for(BlogCategory::factory(), 'category')
        ->create(['title' => 'Draft only']);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('headlinePost', null)
        );
});

test('homepage excludes the headline post from featured and latest lists', function () {
    $category = BlogCategory::factory()->state(['accent' => 'primary'])->create();

    $headline = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->headline()
        ->create([
            'title' => 'Pinned + featured headline',
            'is_featured' => true,
            'published_at' => now()->subHour(),
        ]);

    $otherFeatured = BlogPost::factory()
        ->for($category, 'category')
        ->published()
        ->create([
            'title' => 'Other featured post',
            'is_featured' => true,
            'published_at' => now()->subDay(),
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('headlinePost.slug', $headline->slug)
            ->has('featuredPosts', 1)
            ->where('featuredPosts.0.slug', $otherFeatured->slug)
            ->has('latestPosts', 1)
            ->where('latestPosts.0.slug', $otherFeatured->slug)
        );
});

test('pinned headline still wins over a more recently published post', function () {
    $category = BlogCategory::factory()->state(['accent' => 'primary'])->create();

    $pinned = BlogPost::factory()->for($category, 'category')->published()->headline()->create([
        'title' => 'Older pinned post',
        'published_at' => now()->subDays(7),
    ]);

    BlogPost::factory()->for($category, 'category')->published()->create([
        'title' => 'Newer unpinned post',
        'published_at' => now()->subHour(),
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            ->where('headlinePost.slug', $pinned->slug)
            ->where('headlinePost.title', 'Older pinned post')
        );
});

test('marking a new post as headline auto-unpins the previous one', function () {
    $category = BlogCategory::factory()->state(['accent' => 'primary'])->create();

    $first = BlogPost::factory()->for($category, 'category')->published()->headline()->create([
        'title' => 'First headline',
    ]);

    expect($first->fresh()->is_headline)->toBeTrue();

    $second = BlogPost::factory()->for($category, 'category')->published()->headline()->create([
        'title' => 'Second headline',
    ]);

    expect($first->fresh()->is_headline)->toBeFalse()
        ->and($second->fresh()->is_headline)->toBeTrue();
});
