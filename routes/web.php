<?php

use App\Http\Controllers\NewsletterConfirmationController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\NewsletterUnsubscribeController;
use App\Support\BlogSamplePosts;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('/sign-in', 'Auth/Signin')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');
Route::inertia('/forgot-password', 'Auth/ForgotPassword')->name('password.request');
Route::inertia('/contact', 'Contact')->name('contact');

Route::get('/newsletter', [NewsletterSubscriptionController::class, 'create'])->name('newsletter');
Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])
    ->middleware('throttle:newsletter-subscription')
    ->name('newsletter.store');
Route::get('/newsletter/resend', [NewsletterSubscriptionController::class, 'createResend'])->name('newsletter.resend');
Route::post('/newsletter/resend', [NewsletterSubscriptionController::class, 'resend'])
    ->middleware('throttle:newsletter-resend')
    ->name('newsletter.resend.store');
Route::get('/newsletter/confirm/{token}', NewsletterConfirmationController::class)
    ->name('newsletter.confirm')
    ->where('token', '[A-Za-z0-9]+');
Route::get('/newsletter/unsubscribe/{token}', NewsletterUnsubscribeController::class)
    ->name('newsletter.unsubscribe')
    ->where('token', '[A-Za-z0-9]+');

Route::get('/blog', function () {
    return Inertia::render('Blog/Index', [
        'posts' => BlogSamplePosts::summaries(),
    ]);
})->name('blog.index');

Route::get('/blog/{slug}', function (string $slug) {
    $post = BlogSamplePosts::find($slug);
    abort_if($post === null, 404);

    return Inertia::render('Blog/Show', [
        'post' => $post,
    ]);
})->name('blog.show');

Route::inertia('/legal/privacy', 'Legal/PrivacyPolicy')->name('legal.privacy');
Route::inertia('/legal/terms', 'Legal/TermsOfService')->name('legal.terms');
Route::inertia('/legal/cookies', 'Legal/CookiePolicy')->name('legal.cookies');
