<?php

use App\Models\NewsletterSubscriber;

test('marketing and blog routes return successful responses', function () {
    $this->get(route('home'))->assertOk();
    $this->get(route('contact'))->assertOk();
    $this->get(route('newsletter'))->assertOk();
    $this->get(route('blog.index'))->assertOk();
    $this->get(route('blog.show', ['slug' => 'monolith-to-modular-apis']))->assertOk();
});

test('newsletter subscription stores email and flashes success', function () {
    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), ['email' => 'reader@example.com'])
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'reader@example.com',
    ]);
});

test('newsletter subscription rejects duplicate email', function () {
    NewsletterSubscriber::factory()->create(['email' => 'dup@example.com']);

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), ['email' => 'dup@example.com'])
        ->assertSessionHasErrors('email');
});

test('legal policy pages return successful responses', function () {
    $this->get(route('legal.privacy'))->assertOk();
    $this->get(route('legal.terms'))->assertOk();
    $this->get(route('legal.cookies'))->assertOk();
});

test('blog show returns not found for unknown slug', function () {
    $this->get(route('blog.show', ['slug' => 'unknown-slug-xyz']))->assertNotFound();
});
