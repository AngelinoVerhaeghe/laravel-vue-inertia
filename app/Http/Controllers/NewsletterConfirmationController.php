<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterConfirmationController extends Controller
{
    public function __invoke(string $token): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::query()
            ->where('confirmation_token', $token)
            ->first();

        if ($subscriber === null) {
            throw new NotFoundHttpException;
        }

        if ($subscriber->confirmed_at !== null) {
            return to_route('home')->with(
                'info',
                'Your email is already confirmed—you are on the list.',
            );
        }

        if (
            $subscriber->confirmation_expires_at !== null
            && $subscriber->confirmation_expires_at->isPast()
        ) {
            return to_route('home')->with(
                'info',
                'This confirmation link has expired. Use “Resend confirmation email” on the newsletter page or subscribe again to get a new link.',
            );
        }

        $subscriber->forceFill([
            'confirmed_at' => now(),
            'confirmation_token' => null,
            'confirmation_expires_at' => null,
        ])->save();

        Mail::to($subscriber->email)->queue(new NewsletterWelcome($subscriber));

        return to_route('home')->with(
            'success',
            'You are subscribed—thanks for confirming. Welcome aboard!',
        );
    }
}
