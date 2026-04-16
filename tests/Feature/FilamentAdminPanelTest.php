<?php

use App\Models\User;

test('removed public auth routes return not found', function () {
    $this->get('/sign-in')->assertNotFound();
    $this->get('/register')->assertNotFound();
    $this->get('/forgot-password')->assertNotFound();
});

test('guest is redirected from dashboard to dashboard login', function () {
    $this->get('/dashboard')
        ->assertRedirect(route('filament.dashboard.auth.login'));
});

test('non-admin user cannot access dashboard', function () {
    config(['filament.admin_email' => 'admin@example.com']);

    $user = User::factory()->create(['email' => 'other@example.com']);

    $this->actingAs($user)->get('/dashboard')->assertForbidden();
});

test('admin user can access dashboard', function () {
    config(['filament.admin_email' => 'admin@example.com']);

    $user = User::factory()->create(['email' => 'admin@example.com']);

    $this->actingAs($user)->get('/dashboard')->assertSuccessful();
});
