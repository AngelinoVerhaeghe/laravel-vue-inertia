<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BlogPagination from '@/components/blog/BlogPagination.vue';
import type {PaginationPayload} from '@/components/blog/BlogPagination.vue';
import SeoHead from '@/components/SeoHead.vue';
import type {SeoPayload} from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { category as blogCategory, index as blogIndex, show as blogShow } from '@/routes/blog';

export interface BlogPostSummary {
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

export interface ArchiveDescriptor {
    type: 'category' | 'tag';
    name: string;
    slug: string;
    accent?: string;
}

defineProps<{
    archive: ArchiveDescriptor;
    posts: BlogPostSummary[];
    pagination: PaginationPayload;
    seo?: Partial<SeoPayload> | null;
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
    <SeoHead :seo="seo ?? undefined" />

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
                <nav class="mb-6 text-sm" aria-label="Breadcrumb">
                    <ol
                        class="flex flex-wrap items-center gap-1.5 text-slate-500"
                        role="list"
                    >
                        <li>
                            <Link
                                href="/"
                                class="transition hover:text-teal-700"
                            >
                                Home
                            </Link>
                        </li>
                        <li aria-hidden="true" class="text-slate-300">/</li>
                        <li>
                            <Link
                                :href="blogIndex.url()"
                                class="transition hover:text-teal-700"
                            >
                                Blog
                            </Link>
                        </li>
                        <li aria-hidden="true" class="text-slate-300">/</li>
                        <li
                            class="font-medium text-slate-700"
                            aria-current="page"
                        >
                            {{
                                archive.type === 'tag'
                                    ? `#${archive.name}`
                                    : archive.name
                            }}
                        </li>
                    </ol>
                </nav>
                <p
                    v-if="archive.type === 'category'"
                    v-reveal
                    class="mb-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold tracking-wider uppercase ring-1"
                    :class="tagClasses(archive.accent ?? 'primary')"
                >
                    Category
                </p>
                <p
                    v-else
                    v-reveal
                    class="mb-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold tracking-wider text-slate-700 uppercase ring-1 ring-slate-200/80"
                >
                    Tag
                </p>
                <h1
                    v-reveal="1"
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    {{
                        archive.type === 'tag'
                            ? `#${archive.name}`
                            : archive.name
                    }}
                </h1>
                <p v-reveal="2" class="mt-4 max-w-2xl text-lg text-slate-600">
                    {{
                        archive.type === 'category'
                            ? `Posts in the ${archive.name} category.`
                            : `Posts tagged ${archive.name}.`
                    }}
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div
                v-if="posts.length === 0"
                v-reveal
                class="rounded-2xl border border-slate-200/80 bg-white p-8 text-center shadow-sm"
            >
                <p
                    class="inline-flex rounded-full bg-teal-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-teal-900 uppercase ring-1 ring-teal-300/60"
                >
                    No posts yet
                </p>
                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-800">
                    Nothing here just yet.
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">
                    {{
                        archive.type === 'category'
                            ? 'No posts have been published in this category yet.'
                            : 'No posts have been tagged with this yet.'
                    }}
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <Link
                        :href="blogIndex.url()"
                        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-500"
                    >
                        Browse all posts
                    </Link>
                </div>
            </div>

            <ul v-else class="space-y-6" role="list">
                <li
                    v-for="(post, index) in posts"
                    :key="post.slug"
                    v-reveal="index"
                >
                    <Link
                        :href="blogShow.url(post.slug)"
                        class="block focus:outline-none"
                    >
                        <article
                            class="group rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white sm:flex sm:gap-8 sm:p-8"
                            :class="cardHoverClasses(post.accent)"
                        >
                            <div class="min-w-0 flex-1">
                                <Link
                                    :href="blogCategory.url(post.categorySlug)"
                                    as="button"
                                    type="button"
                                    class="inline-flex cursor-pointer rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset transition hover:opacity-80"
                                    :class="tagClasses(post.accent)"
                                    @click.stop
                                >
                                    {{ post.category }}
                                </Link>
                                <h2
                                    class="mt-3 text-xl font-bold tracking-tight text-slate-800 transition group-hover:text-teal-800 sm:text-2xl"
                                >
                                    {{ post.title }}
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
                                    <span
                                        class="ml-auto font-semibold normal-case text-teal-700 transition group-hover:text-teal-600"
                                    >
                                        Read article →
                                    </span>
                                </div>
                            </div>
                        </article>
                    </Link>
                </li>
            </ul>

            <BlogPagination v-if="posts.length" :pagination="pagination" />
        </section>
    </MarketingLayout>
</template>
