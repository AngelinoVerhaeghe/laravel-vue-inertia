<script setup lang="ts">
import HeroTopicChips from '@/components/marketing/HeroTopicChips.vue';
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Contact — Stack Notes',
            description:
                'Get in touch with Stack Notes about Laravel, Vue, and full-stack web development articles, collaboration ideas, or suggestions for upcoming tutorials and field notes.',
        }"
    />

    <MarketingLayout active-nav="contact">
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
                    Contact
                </p>
                <h1
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    Say hello
                </h1>
                <p
                    class="mt-4 max-w-2xl text-lg leading-relaxed text-slate-600"
                >
                    Have a question about a Laravel API pattern, a Vue component
                    issue, or want to suggest a topic for the next deep dive?
                    Drop a short note — collaboration ideas, corrections to
                    existing posts, and reader questions are all welcome. We
                    read everything that comes through the form and occasionally
                    turn the best questions into new articles.
                </p>

                <HeroTopicChips class="mt-6" />
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-5 lg:gap-16">
                <div class="lg:col-span-3">
                    <form
                        class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
                        action="#"
                        @submit.prevent
                    >
                        <div>
                            <label
                                for="contact-name"
                                class="block text-sm font-medium text-slate-800"
                                >Name</label
                            >
                            <input
                                id="contact-name"
                                type="text"
                                name="name"
                                autocomplete="name"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="Ada Lovelace"
                            />
                        </div>
                        <div>
                            <label
                                for="contact-email"
                                class="block text-sm font-medium text-slate-800"
                                >Email</label
                            >
                            <input
                                id="contact-email"
                                type="email"
                                name="email"
                                autocomplete="email"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="you@example.com"
                            />
                        </div>
                        <div>
                            <label
                                for="contact-message"
                                class="block text-sm font-medium text-slate-800"
                                >Message</label
                            >
                            <textarea
                                id="contact-message"
                                name="message"
                                rows="6"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="What’s on your mind?"
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-linear-to-r from-teal-600 to-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-600/20 transition hover:from-teal-500 hover:to-teal-600 sm:w-auto"
                        >
                            Send message
                        </button>
                    </form>
                </div>

                <aside class="lg:col-span-2">
                    <div
                        class="rounded-2xl border border-violet-200/80 bg-linear-to-br from-violet-50/90 to-white p-6"
                    >
                        <h2
                            class="font-heading text-lg font-bold text-slate-800"
                        >
                            Other ways to reach us
                        </h2>
                        <ul class="mt-4 space-y-4 text-sm text-slate-600">
                            <li>
                                <span class="font-medium text-violet-800"
                                    >Email</span
                                >
                                <p class="mt-1">
                                    <a
                                        href="mailto:hello@stacknotes.test"
                                        class="text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                                        >hello@stacknotes.test</a
                                    >
                                </p>
                            </li>
                            <li>
                                <span class="font-medium text-violet-800"
                                    >Response time</span
                                >
                                <p class="mt-1">
                                    We aim to reply within a few business days.
                                </p>
                            </li>
                            <li>
                                <span class="font-medium text-violet-800"
                                    >Collabs</span
                                >
                                <p class="mt-1">
                                    Guest posts and cross-promos—pitch us with
                                    links to your best work.
                                </p>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </MarketingLayout>
</template>
