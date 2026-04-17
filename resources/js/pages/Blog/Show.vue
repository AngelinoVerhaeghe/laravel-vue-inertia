<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SeoHead, { type SeoPayload } from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { category as blogCategory, index as blogIndex, tag as blogTag } from '@/routes/blog';

export interface BlogPost {
    slug: string;
    title: string;
    excerpt: string;
    category: string;
    categorySlug: string;
    date: string;
    dateTime: string;
    readTime: string;
    accent: string;
    featuredImageUrl: string | null;
    tags: Array<{ name: string; slug: string }>;
    bodyHtml: string;
}

export interface Breadcrumb {
    name: string;
    url: string;
}

defineProps<{
    post: BlogPost;
    breadcrumbs?: Breadcrumb[];
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
</script>

<template>
    <SeoHead :seo="seo ?? undefined" />

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
                    <nav
                        v-if="breadcrumbs && breadcrumbs.length"
                        class="mb-6 text-sm"
                        aria-label="Breadcrumb"
                    >
                        <ol
                            class="flex min-w-0 flex-nowrap items-center gap-1.5 overflow-hidden text-slate-500"
                            role="list"
                        >
                            <li
                                v-for="(crumb, index) in breadcrumbs"
                                :key="crumb.url"
                                :class="[
                                    'flex min-w-0 items-center gap-1.5',
                                    index === breadcrumbs.length - 1
                                        ? 'flex-1'
                                        : 'shrink-0',
                                ]"
                            >
                                <span
                                    v-if="index > 0"
                                    class="shrink-0 text-slate-300"
                                    aria-hidden="true"
                                    >/</span
                                >
                                <Link
                                    v-if="index < breadcrumbs.length - 1"
                                    :href="crumb.url"
                                    class="transition hover:text-teal-700"
                                >
                                    {{ crumb.name }}
                                </Link>
                                <span
                                    v-else
                                    class="min-w-0 truncate font-medium text-slate-700"
                                    :title="crumb.name"
                                    aria-current="page"
                                >
                                    {{ crumb.name }}
                                </span>
                            </li>
                        </ol>
                    </nav>

                    <Link
                        :href="blogIndex.url()"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-teal-700 transition hover:text-teal-600"
                    >
                        ← Back to blog
                    </Link>
                    <p class="mt-8">
                        <Link
                            :href="blogCategory.url(post.categorySlug)"
                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset transition hover:opacity-80"
                            :class="tagClasses(post.accent)"
                        >
                            {{ post.category }}
                        </Link>
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

                    <ul
                        v-if="post.tags.length"
                        class="mt-5 flex flex-wrap gap-2"
                        aria-label="Tags"
                    >
                        <li v-for="tag in post.tags" :key="tag.slug">
                            <Link
                                :href="blogTag.url(tag.slug)"
                                class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200/80 transition hover:bg-slate-200 hover:text-slate-900"
                            >
                                #{{ tag.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </header>

            <div
                class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8"
            >
                <img
                    v-if="post.featuredImageUrl"
                    :src="post.featuredImageUrl"
                    :alt="post.title"
                    class="mb-10 w-full rounded-2xl border border-slate-200/80 shadow-sm"
                    loading="lazy"
                />

                <div class="blog-markdown" data-markdown v-html="post.bodyHtml" />

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

                <nav
                    class="mt-10 border-t border-slate-200/80 pt-8"
                    aria-label="Post footer"
                >
                    <Link
                        :href="blogIndex.url()"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-teal-700 transition hover:text-teal-600"
                    >
                        ← Back to blog
                    </Link>
                </nav>
            </div>
        </article>
    </MarketingLayout>
</template>

<style scoped>
.blog-markdown :deep(h1),
.blog-markdown :deep(h2),
.blog-markdown :deep(h3) {
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: rgb(30 41 59);
}

.blog-markdown :deep(h1) {
    font-size: 1.875rem;
    line-height: 2.25rem;
}

.blog-markdown :deep(h2) {
    font-size: 1.5rem;
    line-height: 2rem;
}

.blog-markdown :deep(h3) {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.blog-markdown :deep(p) {
    margin-bottom: 1.25rem;
    line-height: 1.75;
    color: rgb(71 85 105);
}

.blog-markdown :deep(a) {
    color: rgb(15 118 110);
    font-weight: 600;
    text-decoration: none;
}

.blog-markdown :deep(a:hover) {
    color: rgb(13 148 136);
    text-decoration: underline;
}

.blog-markdown :deep(ul),
.blog-markdown :deep(ol) {
    margin: 1rem 0 1.25rem;
    padding-left: 1.25rem;
    color: rgb(71 85 105);
}

.blog-markdown :deep(li) {
    margin: 0.25rem 0;
}

.blog-markdown :deep(pre) {
    margin: 1.25rem 0;
    overflow-x: auto;
    border-radius: 1rem;
    border: 1px solid rgb(226 232 240 / 0.8);
    background: rgb(15 23 42);
    padding: 1rem;
}

.blog-markdown :deep(code) {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,
        'Liberation Mono', 'Courier New', monospace;
    font-size: 0.875rem;
}

.blog-markdown :deep(pre code.hljs) {
    display: block;
    padding: 0;
    background: transparent;
}

.blog-markdown :deep(:not(pre) > code) {
    border-radius: 0.5rem;
    background: rgb(15 23 42 / 0.06);
    padding: 0.125rem 0.375rem;
    color: rgb(15 23 42);
}

.blog-markdown :deep(blockquote) {
    margin: 1.25rem 0;
    border-left: 3px solid rgb(20 184 166);
    padding-left: 1rem;
    color: rgb(71 85 105);
}

.blog-markdown :deep(hr) {
    margin: 2rem 0;
    border: 0;
    border-top: 1px solid rgb(226 232 240 / 0.8);
}
</style>
