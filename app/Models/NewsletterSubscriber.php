<?php

namespace App\Models;

use Database\Factories\NewsletterSubscriberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['email'])]
class NewsletterSubscriber extends Model
{
    /** @use HasFactory<NewsletterSubscriberFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unsubscribed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'confirmation_expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber): void {
            if ($subscriber->unsubscribe_token === null || $subscriber->unsubscribe_token === '') {
                $subscriber->unsubscribe_token = Str::random(48);
            }

            if ($subscriber->confirmed_at === null && ($subscriber->confirmation_token === null || $subscriber->confirmation_token === '')) {
                $subscriber->confirmation_token = Str::random(64);
            }

            if ($subscriber->confirmation_token && $subscriber->confirmed_at === null) {
                $subscriber->confirmation_expires_at ??= now()->addDays(self::confirmationTtlDays());
            }
        });
    }

    public static function confirmationTtlDays(): int
    {
        return max(1, (int) config('newsletter.confirmation_ttl_days', 7));
    }

    /**
     * Confirmed and still receiving the newsletter (not unsubscribed).
     */
    public function isActiveSubscriber(): bool
    {
        return $this->confirmed_at !== null && $this->unsubscribed_at === null;
    }

    /**
     * Signed up but not yet confirmed, and not in an unsubscribed state.
     */
    public function hasPendingConfirmation(): bool
    {
        return $this->confirmed_at === null && $this->unsubscribed_at === null;
    }

    /**
     * Issue a new confirmation token and expiry window (pending re-send or resubscribe).
     */
    public function issueNewConfirmationWindow(): void
    {
        $this->forceFill([
            'confirmation_token' => Str::random(64),
            'confirmation_expires_at' => now()->addDays(self::confirmationTtlDays()),
        ])->save();
    }

    /**
     * Former subscriber is signing up again: clear unsubscribe, reset double opt-in.
     */
    public function reactivateAfterUnsubscribe(): void
    {
        $this->forceFill([
            'unsubscribed_at' => null,
            'confirmed_at' => null,
            'unsubscribe_token' => Str::random(48),
            'confirmation_token' => Str::random(64),
            'confirmation_expires_at' => now()->addDays(self::confirmationTtlDays()),
        ])->save();
    }

    /**
     * Active subscription: not unsubscribed and double opt-in completed.
     *
     * @param  Builder<NewsletterSubscriber>  $query
     * @return Builder<NewsletterSubscriber>
     */
    public function scopeSubscribed(Builder $query): Builder
    {
        return $query
            ->whereNull('unsubscribed_at')
            ->whereNotNull('confirmed_at');
    }
}
