<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsletterUnsubscribeController extends Controller
{
    public function __invoke(string $token): Response
    {
        $subscriber = NewsletterSubscriber::query()
            ->where('unsubscribe_token', $token)
            ->first();

        if ($subscriber === null) {
            throw new NotFoundHttpException;
        }

        $alreadyUnsubscribed = $subscriber->unsubscribed_at !== null;

        if (! $alreadyUnsubscribed) {
            $subscriber->forceFill(['unsubscribed_at' => now()])->save();
        }

        return Inertia::render('Newsletter/Unsubscribed', [
            'alreadyUnsubscribed' => $alreadyUnsubscribed,
        ]);
    }
}
