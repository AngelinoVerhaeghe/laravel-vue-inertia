<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterIssue extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterSubscriber $subscriber,
        public string $unsubscribeUrl,
    ) {}

    public function envelope(): Envelope
    {
        $month = now()->translatedFormat('F Y');

        return new Envelope(
            subject: "Stack Notes — {$month} newsletter",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter.issue',
            with: [
                'subscriber' => $this->subscriber,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
