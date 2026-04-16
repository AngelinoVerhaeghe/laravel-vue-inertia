<script setup lang="ts">
import {
    create as newsletterPage,
    resend,
} from '@/actions/App/Http/Controllers/NewsletterSubscriptionController';
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { privacy } from '@/routes/legal';

const form = useForm({
    email: '',
    /** Honeypot — must stay empty (see ResendNewsletterConfirmationRequest). */
    newsletter_company_website: '',
});

function submit(): void {
    form.submit(resend());
}
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Resend confirmation — Stack Notes',
            description:
                'Didn\u2019t receive your confirmation email? Request a new Stack Notes newsletter confirmation link.',
            noindex: true,
        }"
    />

    <MarketingLayout active-nav="newsletter">
        <section
            class="relative overflow-hidden border-b border-slate-200/60 bg-white/60"
        >
            <div
                class="pointer-events-none absolute inset-0"
                aria-hidden="true"
            >
                <div
                    class="absolute -top-24 right-0 h-80 w-80 rounded-full bg-amber-200/35 blur-3xl"
                />
                <div
                    class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-teal-200/25 blur-3xl"
                />
            </div>

            <div
                class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8"
            >
                <p
                    class="mb-3 inline-flex rounded-full bg-amber-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-amber-900 uppercase ring-1 ring-amber-300/60"
                >
                    Newsletter
                </p>
                <h1
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    Resend confirmation email
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-slate-600">
                    Enter the address you used to subscribe. If it has a
                    <em>pending</em> signup (not yet confirmed), we will send a
                    new confirmation link. Already left the list? Subscribe
                    again from the main newsletter page.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-xl">
                <form
                    class="relative space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
                    @submit.prevent="submit"
                >
                    <div
                        class="pointer-events-none absolute top-0 left-0 -z-10 h-px w-px overflow-hidden opacity-0"
                        aria-hidden="true"
                    >
                        <label for="resend-newsletter-company-website"
                            >Company website</label
                        >
                        <input
                            id="resend-newsletter-company-website"
                            v-model="form.newsletter_company_website"
                            type="text"
                            name="newsletter_company_website"
                            tabindex="-1"
                            autocomplete="off"
                        />
                    </div>
                    <div>
                        <label
                            for="resend-email"
                            class="block text-sm font-medium text-slate-800"
                            >Email</label
                        >
                        <input
                            id="resend-email"
                            v-model="form.email"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                            class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                            :class="
                                form.errors.email
                                    ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                                    : ''
                            "
                            placeholder="you@example.com"
                        />
                        <p
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-500">
                        See our
                        <Link
                            :href="privacy.url()"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >Privacy Policy</Link
                        >.
                    </p>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-linear-to-r from-teal-600 to-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-600/20 transition hover:from-teal-500 hover:to-teal-600 disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto"
                            :disabled="form.processing"
                        >
                            Send again
                        </button>
                        <Link
                            :href="newsletterPage.url()"
                            class="text-center text-sm font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600 sm:text-left"
                        >
                            Back to newsletter
                        </Link>
                    </div>
                </form>
            </div>
        </section>
    </MarketingLayout>
</template>
