<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { category as blogCategory, show as blogShow } from '@/routes/blog';

export interface RelatedPostCard {
    slug: string;
    title: string;
    excerpt: string;
    category: string;
    categorySlug: string;
    date: string;
    dateTime: string;
    readTime: string;
    accent: string;
}

defineProps<{
    heading: string;
    posts: RelatedPostCard[];
}>();

function tagClasses(accent: string): string {
    const map: Record<string, string> = {
        amber: 'bg-amber-100 text-amber-900 ring-amber-200/80',
        primary: 'bg-teal-100 text-teal-900 ring-teal-200/80',
        secondary: 'bg-violet-100 text-violet-900 ring-violet-200/80',
        slate: 'bg-slate-100 text-slate-900 ring-slate-200/80',
        sky: 'bg-sky-100 text-sky-900 ring-sky-200/80',
        rose: 'bg-rose-100 text-rose-900 ring-rose-200/80',
    };

    return map[accent] ?? map.primary;
}

function cardHoverClasses(accent: string): string {
    const map: Record<string, string> = {
        amber: 'hover:border-amber-200/80 hover:shadow-amber-600/5',
        primary: 'hover:border-teal-200/80 hover:shadow-teal-600/5',
        secondary: 'hover:border-violet-200/80 hover:shadow-violet-600/5',
        slate: 'hover:border-slate-200/80 hover:shadow-slate-600/5',
        sky: 'hover:border-sky-200/80 hover:shadow-sky-600/5',
        rose: 'hover:border-rose-200/80 hover:shadow-rose-600/5',
    };

    return map[accent] ?? map.primary;
}
</script>

<template>
    <section v-if="posts.length" class="mt-14">
        <h2
            v-reveal
            class="text-xl font-bold tracking-tight text-slate-800 sm:text-2xl"
        >
            {{ heading }}
        </h2>
        <ul
            class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
            role="list"
        >
            <li
                v-for="(post, index) in posts"
                :key="post.slug"
                v-reveal="index"
            >
                <Link
                    :href="blogShow.url(post.slug)"
                    class="block h-full focus:outline-none"
                >
                    <article
                        class="group flex h-full flex-col rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:shadow-md focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                        :class="cardHoverClasses(post.accent)"
                    >
                        <Link
                            :href="blogCategory.url(post.categorySlug)"
                            as="button"
                            type="button"
                            class="inline-flex w-fit cursor-pointer rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset transition hover:opacity-80"
                            :class="tagClasses(post.accent)"
                            @click.stop
                        >
                            {{ post.category }}
                        </Link>
                        <h3
                            class="mt-3 text-base font-bold tracking-tight text-slate-800 transition group-hover:text-teal-800 sm:text-lg"
                        >
                            {{ post.title }}
                        </h3>
                        <p
                            class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-600"
                        >
                            {{ post.excerpt }}
                        </p>
                        <div
                            class="mt-4 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500 uppercase"
                        >
                            <time
                                class="tabular-nums"
                                :datetime="post.dateTime"
                                >{{ post.date }}</time
                            >
                            <span aria-hidden="true">·</span>
                            <span>{{ post.readTime }} read</span>
                        </div>
                    </article>
                </Link>
            </li>
        </ul>
    </section>
</template>
