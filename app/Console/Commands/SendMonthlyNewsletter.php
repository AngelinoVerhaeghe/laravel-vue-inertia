<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterIssueMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('newsletter:send')]
#[Description('Queue the monthly newsletter email to all active subscribers')]
class SendMonthlyNewsletter extends Command
{
    public function handle(): int
    {
        $count = 0;

        NewsletterSubscriber::query()
            ->subscribed()
            ->whereNotNull('unsubscribe_token')
            ->chunkById(100, function ($subscribers) use (&$count): void {
                foreach ($subscribers as $subscriber) {
                    SendNewsletterIssueMail::dispatch($subscriber);
                    $count++;
                }
            });

        $this->info("Queued {$count} newsletter message(s).");

        return self::SUCCESS;
    }
}
