<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendNewsletterConfirmationRequest;
use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Mail\NewsletterConfirmSubscription;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterSubscriptionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Newsletter');
    }

    public function createResend(): Response
    {
        return Inertia::render('Newsletter/ResendConfirmation');
    }

    public function store(StoreNewsletterSubscriberRequest $request): RedirectResponse
    {
        $email = $request->validated('email');
        $existing = NewsletterSubscriber::query()->where('email', $email)->first();

        if ($existing !== null && $existing->isActiveSubscriber()) {
            return redirect()
                ->back()
                ->withErrors([
                    'email' => 'This email is already on the list—thank you for your interest.',
                ])
                ->onlyInput('email');
        }

        if ($existing === null) {
            $subscriber = NewsletterSubscriber::query()->create(['email' => $email]);
            $message = 'Check your email to confirm your subscription. We only add you to the list after you click the link in that message.';
        } elseif ($existing->unsubscribed_at !== null) {
            $existing->reactivateAfterUnsubscribe();
            $subscriber = $existing->refresh();
            $message = 'Check your email to confirm your subscription. We only add you to the list after you click the link in that message.';
        } elseif ($existing->hasPendingConfirmation()) {
            $existing->issueNewConfirmationWindow();
            $subscriber = $existing->refresh();
            $message = 'We sent a fresh confirmation link—check your email (the previous link no longer works).';
        } else {
            return redirect()
                ->route('newsletter')
                ->with(
                    'error',
                    'We could not process that signup. Please try again or contact us.',
                );
        }

        $this->queueConfirmationMail($subscriber);

        return to_route('home')->with('success', $message);
    }

    public function resend(ResendNewsletterConfirmationRequest $request): RedirectResponse
    {
        $email = $request->validated('email');

        $subscriber = NewsletterSubscriber::query()
            ->where('email', $email)
            ->first();

        if ($subscriber !== null && $subscriber->hasPendingConfirmation()) {
            $subscriber->issueNewConfirmationWindow();
            $subscriber->refresh();
            $this->queueConfirmationMail($subscriber);
        }

        return redirect()
            ->route('newsletter.resend')
            ->with(
                'success',
                'If that address has a pending subscription, we have sent a new confirmation email. Check your inbox and spam folder.',
            );
    }

    private function queueConfirmationMail(NewsletterSubscriber $subscriber): void
    {
        $confirmUrl = route('newsletter.confirm', [
            'token' => $subscriber->confirmation_token,
        ]);

        Mail::to($subscriber->email)->queue(new NewsletterConfirmSubscription($subscriber, $confirmUrl));
    }
}
