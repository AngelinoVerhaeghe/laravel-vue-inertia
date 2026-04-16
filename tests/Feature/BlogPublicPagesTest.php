<?php

use App\Models\BlogCategory;
use App\Models\BlogPost;

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
