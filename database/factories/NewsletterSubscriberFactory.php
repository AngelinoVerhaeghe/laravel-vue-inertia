<?php

namespace Database\Factories;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'confirmed_at' => now(),
            'confirmation_token' => null,
            'confirmation_expires_at' => null,
        ];
    }

    /**
     * Pending double opt-in (not yet confirmed).
     */
    public function unconfirmed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'confirmed_at' => null,
            'confirmation_token' => Str::random(64),
            'confirmation_expires_at' => now()->addDays(NewsletterSubscriber::confirmationTtlDays()),
        ]);
    }

    /**
     * Confirmed in the past, then unsubscribed (may subscribe again).
     */
    public function unsubscribed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'confirmed_at' => now(),
            'unsubscribed_at' => now(),
            'confirmation_token' => null,
            'confirmation_expires_at' => null,
        ]);
    }
}
