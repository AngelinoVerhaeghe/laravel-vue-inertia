<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterSubscriptionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Newsletter');
    }

    public function store(StoreNewsletterSubscriberRequest $request): RedirectResponse
    {
        NewsletterSubscriber::query()->create($request->validated());

        return to_route('newsletter')->with(
            'success',
            'Thanks for subscribing! You will hear from us when the next issue goes out.',
        );
    }
}
