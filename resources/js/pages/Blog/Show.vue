<script setup lang="ts">
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { index as blogIndex } from '@/routes/blog';

export interface BlogPost {
    slug: string;
    title: string;
    excerpt: string;
    category: string;
    date: string;
    dateTime: string;
    readTime: string;
    accent: string;
    body: string[];
}

defineProps<{
    post: BlogPost;
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
    <Head :title="`${post.title} — Stack Notes`" />

    <MarketingLayout active-nav="blog">
        <article>
            <header
                class="relative overflow-hidden border-b border-slate-200/60 bg-white/70"
            >
                <div
                    class="pointer-events-none absolute inset-0"
                    aria-hidden="true"
                >
                    <div
                        class="absolute top-0 right-0 h-64 w-64 rounded-full bg-violet-200/25 blur-3xl"
                    />
                    <div
                        class="absolute bottom-0 left-0 h-72 w-72 rounded-full bg-amber-200/25 blur-3xl"
                    />
                </div>
                <div
                    class="relative mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8"
                >
                    <Link
                        :href="blogIndex.url()"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-teal-700 transition hover:text-teal-600"
                    >
                        ← Back to blog
                    </Link>
                    <p class="mt-8">
                        <span
                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset"
                            :class="tagClasses(post.accent)"
                        >
                            {{ post.category }}
                        </span>
                    </p>
                    <h1
                        class="mt-4 text-3xl font-bold tracking-tight text-slate-800 sm:text-4xl lg:text-5xl"
                    >
                        {{ post.title }}
                    </h1>
                    <p class="mt-4 text-lg leading-relaxed text-slate-600">
                        {{ post.excerpt }}
                    </p>
                    <p
                        class="mt-6 flex flex-wrap gap-3 text-sm text-slate-500"
                    >
                        <time class="tabular-nums" :datetime="post.dateTime">{{
                            post.date
                        }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ post.readTime }} read</span>
                    </p>
                </div>
            </header>

            <div
                class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8"
            >
                <div>
                    <p
                        v-for="(paragraph, index) in post.body"
                        :key="index"
                        class="mb-6 text-base text-slate-600 last:mb-0"
                    >
                        {{ paragraph }}
                    </p>
                </div>

                <div
                    class="mt-12 rounded-2xl border border-amber-200/80 bg-linear-to-br from-amber-50 to-white p-6"
                >
                    <p class="font-heading text-lg font-bold text-slate-800">
                        Enjoyed this note?
                    </p>
                    <p class="mt-1 text-sm text-slate-600">
                        More articles on the
                        <Link
                            :href="blogIndex.url()"
                            class="font-semibold text-teal-700 hover:text-teal-600"
                            >blog overview</Link
                        >.
                    </p>
                </div>
            </div>
        </article>
    </MarketingLayout>
</template>
