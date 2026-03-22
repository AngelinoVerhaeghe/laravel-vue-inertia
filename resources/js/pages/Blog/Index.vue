<script setup lang="ts">
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { show as blogShow } from '@/routes/blog';

export interface BlogPostSummary {
    slug: string;
    title: string;
    excerpt: string;
    category: string;
    date: string;
    dateTime: string;
    readTime: string;
    accent: string;
}

defineProps<{
    posts: BlogPostSummary[];
}>();

function tagClasses(accent: string): string {
    const map: Record<string, string> = {
        amber: 'bg-amber-100 text-amber-900 ring-amber-200/80',
        primary: 'bg-teal-100 text-teal-900 ring-teal-200/80',
        secondary: 'bg-violet-100 text-violet-900 ring-violet-200/80',
    };

    return map[accent] ?? map.primary;
}
</script>

<template>
    <Head title="Blog — Stack Notes" />

    <MarketingLayout active-nav="blog">
        <section class="relative overflow-hidden border-b border-slate-200/60">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div
                    class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-teal-200/30 blur-3xl"
                />
                <div
                    class="absolute bottom-0 left-1/4 h-56 w-56 rounded-full bg-amber-200/30 blur-3xl"
                />
            </div>
            <div
                class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8"
            >
                <p
                    class="mb-3 inline-flex rounded-full bg-teal-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-teal-900 uppercase ring-1 ring-teal-300/60"
                >
                    Blog
                </p>
                <h1
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    Notes on tech, web & full-stack
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-slate-600">
                    Longer write-ups and practical checklists—newest first.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <ul class="space-y-6" role="list">
                <li v-for="post in posts" :key="post.slug">
                    <article
                        class="group rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:border-teal-200/80 hover:shadow-md hover:shadow-teal-600/5 sm:flex sm:gap-8 sm:p-8"
                    >
                        <div class="min-w-0 flex-1">
                            <span
                                class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset"
                                :class="tagClasses(post.accent)"
                            >
                                {{ post.category }}
                            </span>
                            <h2
                                class="mt-3 text-xl font-bold tracking-tight text-slate-800 sm:text-2xl"
                            >
                                <Link
                                    :href="blogShow.url(post.slug)"
                                    class="transition hover:text-teal-800"
                                >
                                    {{ post.title }}
                                </Link>
                            </h2>
                            <p
                                class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base"
                            >
                                {{ post.excerpt }}
                            </p>
                            <div
                                class="mt-4 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500 uppercase sm:text-sm"
                            >
                                <time
                                    class="tabular-nums"
                                    :datetime="post.dateTime"
                                    >{{ post.date }}</time
                                >
                                <span aria-hidden="true">·</span>
                                <span>{{ post.readTime }} read</span>
                                <Link
                                    :href="blogShow.url(post.slug)"
                                    class="ml-auto font-semibold normal-case text-teal-700 transition hover:text-teal-600"
                                >
                                    Read article →
                                </Link>
                            </div>
                        </div>
                    </article>
                </li>
            </ul>
        </section>
    </MarketingLayout>
</template>
