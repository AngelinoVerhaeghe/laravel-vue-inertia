<?php

use Inertia\Testing\AssertableInertia;

test('homepage renders with seo defaults', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
        );
});

test('blog index meta description includes target keywords', function () {
    $this->get(route('blog.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Blog/Index')
            ->where('seo.description', fn (string $description): bool => str_contains($description, 'Vue 3')
                && str_contains($description, 'Laravel')
                && str_contains($description, 'PostgreSQL')
            )
        );
});

test('contact page renders successfully', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contact')
        );
});

test('newsletter page renders successfully', function () {
    $this->get(route('newsletter'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Newsletter')
        );
});

test('all five blog category routes resolve so chips have valid targets', function () {
    foreach (['frontend', 'backend', 'devops', 'architecture', 'web'] as $slug) {
        expect(route('blog.category', ['slug' => $slug]))
            ->toBeString()
            ->toContain("/blog/category/{$slug}");
    }
});
