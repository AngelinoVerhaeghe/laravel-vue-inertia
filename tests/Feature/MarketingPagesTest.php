<?php

use App\Mail\NewsletterConfirmSubscription;
use App\Mail\NewsletterIssue;
use App\Mail\NewsletterWelcome;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Cache::flush();
});

test('marketing and blog routes return successful responses', function () {
    $post = BlogPost::factory()
        ->for(BlogCategory::factory()->state(['accent' => 'primary']), 'category')
        ->published()
        ->create();

    $this->get(route('home'))->assertOk();
    $this->get(route('contact'))->assertOk();
    $this->get(route('newsletter'))->assertOk();
    $this->get(route('newsletter.resend'))->assertOk();
    $this->get(route('blog.index'))->assertOk();
    $this->get(route('blog.show', ['slug' => $post->slug]))->assertOk();
});

test('newsletter subscription stores pending email and queues confirmation mail', function () {
    Mail::fake();

    $response = $this->from(route('newsletter'))->post(route('newsletter.store'), [
        'email' => 'reader@example.com',
        'newsletter_company_website' => '',
    ]);

    $response->assertRedirectToRoute('home');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'reader@example.com',
        'confirmed_at' => null,
    ]);

    Mail::assertQueued(NewsletterConfirmSubscription::class);
    Mail::assertNotQueued(NewsletterWelcome::class);
});

test('newsletter subscription rejects filled honeypot', function () {
    Mail::fake();

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'human@example.com',
            'newsletter_company_website' => 'https://spam.example',
        ])
        ->assertSessionHasErrors('newsletter_company_website');

    Mail::assertNothingOutgoing();
});

test('newsletter subscription is throttled after three attempts per hour per ip', function () {
    Mail::fake();

    for ($i = 0; $i < 3; $i++) {
        $this->from(route('newsletter'))
            ->post(route('newsletter.store'), [
                'email' => "reader{$i}@example.com",
                'newsletter_company_website' => '',
            ])
            ->assertRedirectToRoute('home');
    }

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'reader4@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirect(route('newsletter'))
        ->assertSessionHas('error');
});

test('newsletter subscription rejects duplicate email', function () {
    Mail::fake();

    NewsletterSubscriber::factory()->create(['email' => 'dup@example.com']);

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'dup@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertSessionHasErrors('email');

    Mail::assertNotQueued(NewsletterConfirmSubscription::class);
});

test('newsletter confirm activates subscription and queues welcome mail', function () {
    Mail::fake();

    $subscriber = NewsletterSubscriber::factory()->unconfirmed()->create([
        'email' => 'pending@example.com',
    ]);

    $this->get(route('newsletter.confirm', ['token' => $subscriber->confirmation_token]))
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    $subscriber->refresh();

    expect($subscriber->confirmed_at)->not->toBeNull()
        ->and($subscriber->confirmation_token)->toBeNull()
        ->and($subscriber->confirmation_expires_at)->toBeNull();

    Mail::assertQueued(NewsletterWelcome::class);
});

test('newsletter confirm redirects with notice when link is expired', function () {
    Mail::fake();

    $subscriber = NewsletterSubscriber::factory()->unconfirmed()->create([
        'confirmation_expires_at' => now()->subDay(),
    ]);

    $this->get(route('newsletter.confirm', ['token' => $subscriber->confirmation_token]))
        ->assertRedirect(route('home'))
        ->assertSessionHas('info');

    Mail::assertNotQueued(NewsletterWelcome::class);
});

test('newsletter subscription reactivates unsubscribed email', function () {
    Mail::fake();

    $subscriber = NewsletterSubscriber::factory()->unsubscribed()->create([
        'email' => 'again@example.com',
    ]);

    expect($subscriber->unsubscribed_at)->not->toBeNull();

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'again@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirectToRoute('home');

    Mail::assertQueued(NewsletterConfirmSubscription::class);

    $subscriber->refresh();

    expect($subscriber->unsubscribed_at)->toBeNull()
        ->and($subscriber->confirmed_at)->toBeNull()
        ->and($subscriber->confirmation_token)->not->toBeEmpty();
});

test('newsletter subscription sends another confirmation when still pending', function () {
    Mail::fake();

    NewsletterSubscriber::factory()->unconfirmed()->create([
        'email' => 'pend@example.com',
    ]);

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'pend@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirectToRoute('home');

    $this->from(route('newsletter'))
        ->post(route('newsletter.store'), [
            'email' => 'pend@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirectToRoute('home');

    Mail::assertQueued(NewsletterConfirmSubscription::class, 2);
});

test('newsletter resend form queues confirmation for pending subscriber', function () {
    Mail::fake();

    NewsletterSubscriber::factory()->unconfirmed()->create([
        'email' => 'resend@example.com',
    ]);

    $this->from(route('newsletter.resend'))
        ->post(route('newsletter.resend.store'), [
            'email' => 'resend@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirect(route('newsletter.resend'))
        ->assertSessionHas('success');

    Mail::assertQueued(NewsletterConfirmSubscription::class);
});

test('newsletter resend form always shows success without leaking enrollment', function () {
    Mail::fake();

    $this->from(route('newsletter.resend'))
        ->post(route('newsletter.resend.store'), [
            'email' => 'unknown-not-registered@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirect(route('newsletter.resend'))
        ->assertSessionHas('success');

    Mail::assertNotQueued(NewsletterConfirmSubscription::class);
});

test('newsletter resend is throttled after three attempts per hour per ip', function () {
    Mail::fake();

    for ($i = 0; $i < 3; $i++) {
        $this->from(route('newsletter.resend'))
            ->post(route('newsletter.resend.store'), [
                'email' => "rs{$i}@example.com",
                'newsletter_company_website' => '',
            ])
            ->assertRedirect(route('newsletter.resend'));
    }

    $this->from(route('newsletter.resend'))
        ->post(route('newsletter.resend.store'), [
            'email' => 'rs4@example.com',
            'newsletter_company_website' => '',
        ])
        ->assertRedirect(route('newsletter.resend'))
        ->assertSessionHas('error');
});

test('newsletter confirm shows notice when already confirmed but token still present', function () {
    Mail::fake();

    $subscriber = NewsletterSubscriber::factory()->create([
        'email' => 'done@example.com',
        'confirmed_at' => now(),
        'confirmation_token' => 'edgecaseconfirmtoken',
    ]);

    $this->get(route('newsletter.confirm', ['token' => 'edgecaseconfirmtoken']))
        ->assertRedirect(route('home'))
        ->assertSessionHas('info');

    Mail::assertNotQueued(NewsletterWelcome::class);
});

test('newsletter confirm returns not found for unknown token', function () {
    $this->get(route('newsletter.confirm', ['token' => 'notarealtoken']))
        ->assertNotFound();
});

test('newsletter unsubscribe returns not found for unknown token', function () {
    $this->get(route('newsletter.unsubscribe', ['token' => 'notarealtoken']))
        ->assertNotFound();
});

test('newsletter unsubscribe sets unsubscribed_at', function () {
    $subscriber = NewsletterSubscriber::factory()->create();

    $this->get(route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]))
        ->assertOk();

    $subscriber->refresh();

    expect($subscriber->unsubscribed_at)->not->toBeNull();
});

test('newsletter send command queues issue only for active subscribers', function () {
    Mail::fake();

    NewsletterSubscriber::factory()->create();
    NewsletterSubscriber::factory()->create([
        'unsubscribed_at' => now(),
    ]);

    $this->artisan('newsletter:send');

    Mail::assertSent(NewsletterIssue::class, 1);
});

test('legal policy pages return successful responses', function () {
    $this->get(route('legal.privacy'))->assertOk();
    $this->get(route('legal.terms'))->assertOk();
    $this->get(route('legal.cookies'))->assertOk();
});

test('blog show returns not found for unknown slug', function () {
    $this->get(route('blog.show', ['slug' => 'unknown-slug-xyz']))->assertNotFound();
});
