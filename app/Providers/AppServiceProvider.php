<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
    }

    /**
     * HTTP rate limits for public forms (abuse protection).
     */
    protected function configureRateLimiting(): void
    {
        $throttleNewsletterForms = $this->newsletterThrottleRedirectResponse();

        RateLimiter::for('newsletter-subscription', function (Request $request) use ($throttleNewsletterForms) {
            return [
                Limit::perMinute(5)->by($request->ip())->response($throttleNewsletterForms),
                Limit::perHour(3)->by($request->ip())->response($throttleNewsletterForms),
            ];
        });

        RateLimiter::for('newsletter-resend', function (Request $request) use ($throttleNewsletterForms) {
            return [
                Limit::perMinute(5)->by($request->ip())->response($throttleNewsletterForms),
                Limit::perHour(3)->by($request->ip())->response($throttleNewsletterForms),
            ];
        });
    }

    /**
     * Friendly throttled UX for Inertia newsletter forms (redirect + flash instead of a raw 429 page).
     *
     * @return Closure(Request $request, array<string, string> $headers): RedirectResponse
     */
    protected function newsletterThrottleRedirectResponse(): Closure
    {
        return function (Request $request, array $headers) {
            $message = 'Too many attempts from this connection. Please wait before trying again.';

            if ($request->routeIs('newsletter.resend.store')) {
                return redirect()->route('newsletter.resend')->with('error', $message);
            }

            return redirect()->route('newsletter')->with('error', $message);
        };
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
