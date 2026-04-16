<script setup lang="ts">
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { contact, newsletter } from '@/routes';
import { index as blogIndex, show as blogShow } from '@/routes/blog';

const featuredPosts = [
    {
        slug: 'monolith-to-modular-apis',
        title: 'From monolith to modular APIs—without the drama',
        excerpt:
            'How to line up endpoints step by step, keep versioning sane, and keep your team grounded during the transition.',
        tag: 'Backend',
        readTime: '9 min',
        accent: 'amber' as const,
    },
    {
        slug: 'inertia-vue-spa-feel',
        title: 'Inertia + Vue 3: SPA feel, server-side confidence',
        excerpt:
            'Why hybrid apps are the sweet spot for many product teams—and patterns you can steal today.',
        tag: 'Web',
        readTime: '7 min',
        accent: 'primary' as const,
    },
    {
        slug: 'css-design-tokens',
        title: 'CSS that scales: design tokens in the real world',
        excerpt:
            'From Figma to Tailwind: naming, themes, and when you actually need a component library.',
        tag: 'Frontend',
        readTime: '6 min',
        accent: 'secondary' as const,
    },
];

const latestPosts = [
    {
        slug: 'monolith-to-modular-apis',
        title: 'From monolith to modular APIs—without the drama',
        date: 'Mar 12, 2026',
        dateTime: '2026-03-12',
        category: 'Backend',
    },
    {
        slug: 'inertia-vue-spa-feel',
        title: 'Inertia + Vue 3: SPA feel, server-side confidence',
        date: 'Mar 8, 2026',
        dateTime: '2026-03-08',
        category: 'Web',
    },
    {
        slug: 'css-design-tokens',
        title: 'CSS that scales: design tokens in the real world',
        date: 'Mar 1, 2026',
        dateTime: '2026-03-01',
        category: 'Frontend',
    },
    {
        slug: 'docker-compose-sanity',
        title: 'Docker Compose for local full-stack: a sanity checklist',
        date: 'Feb 22, 2026',
        dateTime: '2026-02-22',
        category: 'DevOps',
    },
];

function tagClasses(accent: 'amber' | 'primary' | 'secondary'): string {
    const map = {
        amber: 'bg-amber-100 text-amber-900 ring-amber-200/80',
        primary: 'bg-teal-100 text-teal-900 ring-teal-200/80',
        secondary: 'bg-violet-100 text-violet-900 ring-violet-200/80',
    };
    return map[accent];
}
</script>

<template>
    <Head title="Stack Notes — tech, web & full-stack" />

    <MarketingLayout active-nav="home">
        <!-- Hero -->
        <section class="relative overflow-hidden border-b border-slate-200/60">
            <div
                class="pointer-events-none absolute -top-24 -right-32 h-96 w-96 rounded-full bg-amber-200/40 blur-3xl"
            />
            <div
                class="pointer-events-none absolute top-1/2 -left-24 h-72 w-72 -translate-y-1/2 rounded-full bg-teal-200/30 blur-3xl"
            />
            <div
                class="pointer-events-none absolute right-1/4 bottom-0 h-64 w-64 rounded-full bg-violet-200/25 blur-3xl"
            />

            <div
                class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24"
            >
                <p
                    class="mb-4 inline-flex items-center gap-2 rounded-full bg-amber-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-amber-900 uppercase ring-1 ring-amber-300/60"
                >
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-amber-500"
                        aria-hidden="true"
                    />
                    Tech · Web · Development · Full-stack
                </p>
                <h1
                    class="max-w-3xl text-4xl leading-tight font-bold tracking-tight text-slate-800 sm:text-5xl lg:text-6xl"
                >
                    Stories for builders who want to understand the
                    <span class="text-teal-600">whole stack</span>.
                </h1>
                <p
                    class="mt-6 max-w-2xl text-lg leading-relaxed text-slate-600 sm:text-xl"
                >
                    Articles on modern web apps, API design, databases, tooling,
                    and everything in between—practical, no fluff, written like
                    you’re talking to a teammate.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <Link
                        :href="blogIndex.url()"
                        class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-semibold text-amber-950 shadow-lg shadow-amber-500/30 transition hover:bg-amber-400"
                    >
                        Read the latest posts
                    </Link>
                    <Link
                        :href="contact.url()"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-teal-300 hover:text-teal-800"
                    >
                        Get in touch
                    </Link>
                </div>
            </div>
        </section>

        <!-- Featured posts -->
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <h2
                        class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                    >
                        Featured
                    </h2>
                    <p class="mt-2 max-w-xl text-slate-600">
                        Deeper dives—architecture, patterns, and lessons learned
                        from real projects.
                    </p>
                </div>
                <Link
                    :href="blogIndex.url()"
                    class="text-sm font-semibold text-teal-700 hover:text-teal-600"
                >
                    View all →
                </Link>
            </div>

            <ul
                class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
                role="list"
            >
                <li v-for="post in featuredPosts" :key="post.slug">
                    <article
                        class="group flex h-full flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:border-teal-200/80 hover:shadow-md hover:shadow-teal-600/5"
                    >
                        <span
                            class="inline-flex w-fit rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset"
                            :class="tagClasses(post.accent)"
                        >
                            {{ post.tag }}
                        </span>
                        <h3
                            class="mt-4 text-xl leading-snug font-bold text-slate-800 group-hover:text-teal-800"
                        >
                            <Link :href="blogShow.url(post.slug)">{{
                                post.title
                            }}</Link>
                        </h3>
                        <p
                            class="mt-3 flex-1 text-sm leading-relaxed text-slate-600"
                        >
                            {{ post.excerpt }}
                        </p>
                        <p
                            class="mt-4 text-xs font-medium tracking-wide text-slate-500 uppercase"
                        >
                            {{ post.readTime }} read
                        </p>
                    </article>
                </li>
            </ul>
        </section>

        <!-- Latest posts + newsletter -->
        <section
            id="newsletter"
            class="scroll-mt-24 border-t border-slate-200/80 bg-slate-50/80 py-16"
        >
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <h2
                    class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                >
                    Latest notes
                </h2>
                <p class="mt-2 text-slate-600">
                    Short updates and links worth your time.
                </p>

                <ul class="mt-8 divide-y divide-slate-200/90" role="list">
                    <li
                        v-for="row in latestPosts"
                        :key="row.slug"
                        class="flex flex-col gap-1 py-5 first:pt-0 sm:flex-row sm:items-baseline sm:justify-between sm:gap-8"
                    >
                        <div class="min-w-0 flex-1">
                            <Link
                                :href="blogShow.url(row.slug)"
                                class="text-lg font-semibold text-slate-800 transition hover:text-violet-700"
                            >
                                {{ row.title }}
                            </Link>
                            <p class="mt-1 text-sm text-slate-600">
                                <span class="font-medium text-violet-600">{{
                                    row.category
                                }}</span>
                                · practical takeaways for your next sprint
                            </p>
                        </div>
                        <time
                            class="shrink-0 text-sm text-slate-500 tabular-nums"
                            :datetime="row.dateTime"
                        >
                            {{ row.date }}
                        </time>
                    </li>
                </ul>

                <div
                    class="mt-10 flex flex-col gap-5 rounded-2xl border border-amber-200/80 bg-linear-to-br from-amber-50 to-white p-5 sm:p-6 md:flex-row md:items-center md:justify-between md:gap-8"
                >
                    <div class="min-w-0 flex-1">
                        <h3
                            class="font-heading text-xl font-bold text-balance text-slate-800 sm:text-2xl"
                        >
                            Monthly stack letter
                        </h3>
                        <p
                            class="mt-2 max-w-prose text-sm leading-relaxed text-slate-600"
                        >
                            One email, the best links. No spam—promise.
                        </p>
                        <Link
                            :href="newsletter.url()"
                            class="mt-3 inline-flex font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                        >
                            Subscribe on the full page
                        </Link>
                    </div>
                    <div class="flex w-full shrink-0 md:w-auto md:justify-end">
                        <Link
                            :href="newsletter.url()"
                            class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-500 md:min-w-[11rem] md:py-2.5"
                        >
                            Join the list
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
