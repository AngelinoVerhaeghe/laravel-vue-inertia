<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BlogSearchForm from '@/components/marketing/BlogSearchForm.vue';
import HeroTopicChips from '@/components/marketing/HeroTopicChips.vue';
import HomePillars from '@/components/marketing/HomePillars.vue';
import SeoHead from '@/components/SeoHead.vue';
import type {SeoPayload} from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { contact, newsletter } from '@/routes';
import { category as blogCategory, index as blogIndex, show as blogShow } from '@/routes/blog';

type Accent = 'amber' | 'primary' | 'secondary' | 'slate' | 'sky' | 'rose';

defineProps<{
    featuredPosts: Array<{
        slug: string;
        title: string;
        excerpt: string;
        tag: string;
        categorySlug: string;
        readTime: string;
        accent: Accent;
    }>;
    latestPosts: Array<{
        slug: string;
        title: string;
        date: string;
        dateTime: string;
        category: string;
        categorySlug: string;
    }>;
    headlinePost?: {
        slug: string;
        title: string;
        excerpt: string;
        category: string;
        categorySlug: string;
        accent: Accent;
        date: string;
        dateTime: string;
        readTime: string;
        featuredImageUrl: string | null;
    } | null;
    seo?: Partial<SeoPayload> | null;
}>();

function tagClasses(accent: Accent): string {
    const map: Record<Accent, string> = {
        amber: 'bg-amber-100 text-amber-900 ring-amber-200/80',
        primary: 'bg-teal-100 text-teal-900 ring-teal-200/80',
        secondary: 'bg-violet-100 text-violet-900 ring-violet-200/80',
        slate: 'bg-slate-100 text-slate-900 ring-slate-200/80',
        sky: 'bg-sky-100 text-sky-900 ring-sky-200/80',
        rose: 'bg-rose-100 text-rose-900 ring-rose-200/80',
    };

    return map[accent];
}

function cardHoverClasses(accent: Accent): string {
    const map: Record<Accent, string> = {
        amber: 'hover:border-amber-200/80 hover:shadow-amber-600/5',
        primary: 'hover:border-teal-200/80 hover:shadow-teal-600/5',
        secondary: 'hover:border-violet-200/80 hover:shadow-violet-600/5',
        slate: 'hover:border-slate-200/80 hover:shadow-slate-600/5',
        sky: 'hover:border-sky-200/80 hover:shadow-sky-600/5',
        rose: 'hover:border-rose-200/80 hover:shadow-rose-600/5',
    };

    return map[accent];
}
</script>

<template>
    <SeoHead :seo="seo ?? undefined" />

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
                    v-reveal
                    class="mb-4 inline-flex items-center gap-2 rounded-full bg-amber-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-amber-900 uppercase ring-1 ring-amber-300/60"
                >
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-amber-500"
                        aria-hidden="true"
                    />
                    Tech · Web · Development · Full-stack
                </p>
                <h1
                    v-reveal="1"
                    class="max-w-3xl text-4xl leading-tight font-bold tracking-tight text-slate-800 sm:text-5xl lg:text-6xl"
                >
                    Stories for builders who want to understand the
                    <span class="text-teal-600">whole stack</span>.
                </h1>
                <p
                    v-reveal="2"
                    class="mt-6 max-w-2xl text-lg leading-relaxed text-slate-600 sm:text-xl"
                >
                    Stack Notes is a developer blog about building modern web
                    applications end-to-end — from
                    <strong class="font-semibold text-slate-700">Vue 3</strong>
                    and
                    <strong class="font-semibold text-slate-700">Tailwind</strong>
                    on the frontend to
                    <strong class="font-semibold text-slate-700">Laravel</strong>
                    APIs, PostgreSQL queries, Redis caching, and Docker-based
                    deploys. Each post is a hands-on field note from real
                    projects, not a recycled tutorial.
                </p>
                <p
                    v-reveal="3"
                    class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600"
                >
                    Browse by topic below, or jump straight into the latest
                    articles on full-stack development, performance,
                    accessibility, and the small architectural decisions that
                    compound into shippable software.
                </p>

                <HeroTopicChips v-reveal="4" class="mt-8" />

                <BlogSearchForm v-reveal="5" size="md" class="mt-8" />

                <div v-reveal="6" class="mt-10 flex flex-wrap gap-4">
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

        <!-- Pillars -->
        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div v-reveal>
                <h2
                    class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                >
                    What you'll find here
                </h2>
                <p class="mt-2 max-w-2xl text-slate-600">
                    Three threads run through every post. Pick a pillar to jump
                    straight into matching writing.
                </p>
            </div>
            <HomePillars class="mt-10" />
        </section>

        <!-- Pinned headline -->
        <section
            v-if="headlinePost"
            class="relative border-y border-slate-200/60 bg-slate-50/70 py-14 sm:py-16"
        >
            <div
                class="pointer-events-none absolute inset-0 overflow-hidden"
                aria-hidden="true"
            >
                <div
                    class="absolute -top-16 left-1/3 h-64 w-64 rounded-full bg-teal-200/25 blur-3xl"
                />
            </div>
            <div
                class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8"
            >
                <div
                    v-reveal
                    class="mb-8 flex flex-wrap items-end justify-between gap-3"
                >
                    <div>
                        <p
                            class="inline-flex items-center gap-2 rounded-full bg-amber-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-amber-900 uppercase ring-1 ring-amber-300/60"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-amber-500"
                                aria-hidden="true"
                            />
                            Headline
                        </p>
                        <h2
                            class="mt-3 text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                        >
                            Editor's pick
                        </h2>
                    </div>
                </div>

                <Link
                    :href="blogShow.url(headlinePost.slug)"
                    class="group block focus:outline-none"
                >
                    <article
                        class="grid overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-50 md:grid-cols-2"
                        :class="cardHoverClasses(headlinePost.accent)"
                    >
                        <div
                            v-reveal.left
                            class="relative aspect-[16/10] overflow-hidden bg-slate-100 md:aspect-auto"
                        >
                            <img
                                v-if="headlinePost.featuredImageUrl"
                                :src="headlinePost.featuredImageUrl"
                                :alt="headlinePost.title"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                loading="lazy"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-linear-to-br from-slate-100 via-slate-50 to-white text-sm font-medium text-slate-400"
                                aria-hidden="true"
                            >
                                {{ headlinePost.category }}
                            </div>
                        </div>
                        <div
                            v-reveal.right="1"
                            class="flex flex-col justify-center p-6 sm:p-8 lg:p-10"
                        >
                            <Link
                                :href="blogCategory.url(headlinePost.categorySlug)"
                                as="button"
                                type="button"
                                class="inline-flex w-fit cursor-pointer rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset transition hover:opacity-80"
                                :class="tagClasses(headlinePost.accent)"
                                @click.stop
                            >
                                {{ headlinePost.category }}
                            </Link>
                            <h3
                                class="mt-4 text-2xl leading-tight font-bold text-slate-800 transition group-hover:text-teal-800 sm:text-3xl"
                            >
                                {{ headlinePost.title }}
                            </h3>
                            <p
                                class="mt-3 text-base leading-relaxed text-slate-600"
                            >
                                {{ headlinePost.excerpt }}
                            </p>
                            <div
                                class="mt-5 flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500 uppercase sm:text-sm"
                            >
                                <time
                                    class="tabular-nums"
                                    :datetime="headlinePost.dateTime"
                                    >{{ headlinePost.date }}</time
                                >
                                <span aria-hidden="true">·</span>
                                <span>{{ headlinePost.readTime }} read</span>
                                <span
                                    class="ml-auto font-semibold normal-case text-teal-700 transition group-hover:text-teal-600"
                                >
                                    Read article →
                                </span>
                            </div>
                        </div>
                    </article>
                </Link>
            </div>
        </section>

        <!-- Featured posts -->
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div
                v-reveal
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
                <li
                    v-for="(post, index) in featuredPosts"
                    :key="post.slug"
                    v-reveal="index"
                >
                    <Link
                        :href="blogShow.url(post.slug)"
                        class="block h-full focus:outline-none"
                    >
                        <article
                            class="group flex h-full flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
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
                                {{ post.tag }}
                            </Link>
                            <h3
                                class="mt-4 text-xl leading-snug font-bold text-slate-800 group-hover:text-teal-800"
                            >
                                {{ post.title }}
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
                    </Link>
                </li>
            </ul>

            <p
                v-if="!featuredPosts.length"
                class="mt-10 rounded-2xl border border-slate-200/80 bg-white p-6 text-sm text-slate-600"
            >
                No featured posts yet. Mark a published post as “Featured on
                homepage” in the admin panel to show it here.
            </p>
        </section>

        <!-- Latest posts + newsletter -->
        <section
            id="newsletter"
            class="relative scroll-mt-24 border-t border-slate-200/80 bg-slate-50/80 py-16"
        >
            <div
                class="pointer-events-none absolute inset-0 z-0 overflow-hidden"
                aria-hidden="true"
            >
                <div
                    class="absolute right-0 bottom-10 h-[28rem] w-[28rem] translate-x-1/3 rounded-full bg-violet-300/25 blur-3xl"
                />
            </div>
            <div class="relative z-10 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <h2
                    v-reveal
                    class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                >
                    Latest notes
                </h2>
                <p v-reveal="1" class="mt-2 text-slate-600">
                    Short updates and links worth your time.
                </p>

                <p
                    v-if="!latestPosts.length"
                    v-reveal="2"
                    class="mt-8 rounded-2xl border border-slate-200/80 bg-white p-6 text-sm text-slate-600"
                >
                    No posts yet. New notes will appear here once they are
                    published.
                </p>

                <ul
                    v-else
                    v-reveal.left="2"
                    class="mt-8 divide-y divide-slate-200/90"
                    role="list"
                >
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
                                <Link
                                    :href="blogCategory.url(row.categorySlug)"
                                    class="font-medium text-violet-600 transition hover:text-violet-500"
                                >
                                    {{ row.category }}
                                </Link>
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
                    v-reveal.right="3"
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
