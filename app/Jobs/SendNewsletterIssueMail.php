<?php

namespace App\Jobs;

use App\Mail\NewsletterIssue;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterIssueMail implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public NewsletterSubscriber $subscriber,
    ) {}

    public function handle(): void
    {
        $this->subscriber->refresh();

        if ($this->subscriber->unsubscribed_at !== null) {
            return;
        }

        $unsubscribeUrl = route('newsletter.unsubscribe', [
            'token' => $this->subscriber->unsubscribe_token,
        ]);

        Mail::to($this->subscriber->email)->send(new NewsletterIssue($this->subscriber, $unsubscribeUrl));
    }
}
