<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import {
    createResend,
    store,
} from '@/actions/App/Http/Controllers/NewsletterSubscriptionController';
import HeroTopicChips from '@/components/marketing/HeroTopicChips.vue';
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { privacy } from '@/routes/legal';

const form = useForm({
    email: '',
    /** Honeypot — must stay empty (see StoreNewsletterSubscriberRequest). */
    newsletter_company_website: '',
});

function submit(): void {
    form.submit(store());
}
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Newsletter — Stack Notes',
            description:
                'Subscribe to the Stack Notes monthly newsletter — field notes on Vue, Laravel, PostgreSQL, Docker, and full-stack web development. One email per month, no spam.',
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
                    v-reveal
                    class="mb-3 inline-flex rounded-full bg-amber-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-amber-900 uppercase ring-1 ring-amber-300/60"
                >
                    Newsletter
                </p>
                <h1
                    v-reveal="1"
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    Monthly stack letter
                </h1>
                <p
                    v-reveal="2"
                    class="mt-4 max-w-2xl text-lg leading-relaxed text-slate-600"
                >
                    One email per month with curated links and field notes on
                    full-stack craft — Vue and Tailwind component patterns,
                    Laravel and API design, PostgreSQL and Redis performance,
                    Docker and CI workflows, and the accessibility,
                    observability, and architecture decisions that compound
                    across releases. No daily blasts; we send when we have
                    something worth your inbox.
                </p>

                <HeroTopicChips v-reveal="3" class="mt-6" />
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-5 lg:gap-16">
                <div v-reveal.left class="lg:col-span-3">
                    <form
                        class="relative space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
                        @submit.prevent="submit"
                    >
                        <div
                            class="pointer-events-none absolute top-0 left-0 -z-10 h-px w-px overflow-hidden opacity-0"
                            aria-hidden="true"
                        >
                            <label for="newsletter-company-website"
                                >Company website</label
                            >
                            <input
                                id="newsletter-company-website"
                                v-model="form.newsletter_company_website"
                                type="text"
                                name="newsletter_company_website"
                                tabindex="-1"
                                autocomplete="off"
                            />
                        </div>
                        <div>
                            <label
                                for="newsletter-email"
                                class="block text-sm font-medium text-slate-800"
                                >Email</label
                            >
                            <input
                                id="newsletter-email"
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
                            We will email you a confirmation link—your address
                            is only added after you confirm. Confirmation links
                            expire after a few days; you can
                            <Link
                                :href="createResend.url()"
                                class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                                >resend the confirmation email</Link
                            >
                            or subscribe again with the same address. By
                            subscribing you agree we may use your email to send
                            this newsletter. See our
                            <Link
                                :href="privacy.url()"
                                class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                                >Privacy Policy</Link
                            >
                            for how we handle personal data. You can unsubscribe
                            from any issue (link in the footer of each email)
                            when we start sending.
                        </p>
                        <button
                            type="submit"
                            class="w-full cursor-pointer rounded-xl bg-linear-to-r from-teal-600 to-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-600/20 transition hover:from-teal-500 hover:to-teal-600 disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto"
                            :disabled="form.processing"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>

                <aside v-reveal.right="1" class="lg:col-span-2">
                    <div
                        class="space-y-6 rounded-2xl border border-violet-200/80 bg-linear-to-br from-violet-50/90 to-white p-6"
                    >
                        <div>
                            <h2
                                class="font-heading text-lg font-bold text-slate-800"
                            >
                                What to expect
                            </h2>
                            <ul
                                class="mt-4 list-inside list-disc space-y-3 text-sm text-slate-600"
                            >
                                <li>
                                    Roughly once per month—never more than one
                                    email per month unless we announce something
                                    exceptional.
                                </li>
                                <li>
                                    A handful of high-signal links plus a short
                                    note on why they matter for builders.
                                </li>
                                <li>
                                    No sponsored filler or list resale; this
                                    list is only for Stack Notes updates.
                                </li>
                            </ul>
                        </div>
                        <div class="border-t border-violet-200/60 pt-6">
                            <h2
                                class="font-heading text-lg font-bold text-slate-800"
                            >
                                Already subscribed?
                            </h2>
                            <p class="mt-2 text-sm text-slate-600">
                                If you signed up twice by mistake, you will see
                                a validation message—your address is only stored
                                once.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </MarketingLayout>
</template>
