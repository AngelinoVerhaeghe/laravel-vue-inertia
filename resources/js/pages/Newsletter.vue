<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/NewsletterSubscriptionController';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { privacy } from '@/routes/legal';

const page = usePage();

const flashSuccess = computed(() => page.props.flash?.success ?? null);

const form = useForm({
    email: '',
});

function submit(): void {
    form.submit(store());
}
</script>

<template>
    <Head title="Newsletter — Stack Notes" />

    <MarketingLayout active-nav="newsletter">
        <section class="relative overflow-hidden border-b border-slate-200/60 bg-white/60">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
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
                    Weekly stack letter
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-slate-600">
                    One short email with curated links and notes on full-stack
                    craft—APIs, frontend, databases, and shipping calmly in
                    production. No daily blasts; we send when we have something
                    worth your inbox.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-5 lg:gap-16">
                <div class="lg:col-span-3">
                    <div
                        v-if="flashSuccess"
                        class="mb-6 rounded-xl border border-teal-200 bg-teal-50/90 px-4 py-3 text-sm text-teal-900"
                        role="status"
                    >
                        {{ flashSuccess }}
                    </div>

                    <form
                        class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
                        @submit.prevent="submit"
                    >
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
                            By subscribing you agree we may use your email to
                            send this newsletter. See our
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
                            class="w-full rounded-xl bg-linear-to-r from-teal-600 to-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-600/20 transition hover:from-teal-500 hover:to-teal-600 disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto"
                            :disabled="form.processing"
                        >
                            Subscribe
                        </button>
                    </form>
                </div>

                <aside class="lg:col-span-2">
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
                                    Roughly weekly issues while we are ramping
                                    up—never more than one email per week unless
                                    we announce something exceptional.
                                </li>
                                <li>
                                    A handful of high-signal links plus a short
                                    note on why they matter for builders.
                                </li>
                                <li>
                                    No sponsored filler or list resale; this list
                                    is only for Stack Notes updates.
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
                                If you signed up twice by mistake, you will see a
                                validation message—your address is only stored
                                once.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </MarketingLayout>
</template>
