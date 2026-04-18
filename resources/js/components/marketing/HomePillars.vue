<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { index as blogIndex } from '@/routes/blog';

type Accent = 'amber' | 'primary' | 'secondary' | 'slate' | 'sky' | 'rose';

interface Pillar {
    title: string;
    description: string;
    accent: Accent;
    query: string;
    iconPath: string;
}

const pillars: Pillar[] = [
    {
        title: 'Frontend',
        description:
            'Component patterns, design systems, and the UX details that make an interface feel fast and calm — whichever framework you reach for.',
        accent: 'secondary',
        query: 'frontend',
        iconPath:
            'M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zM2 18h20M9 22h6',
    },
    {
        title: 'Backend',
        description:
            'API design, data modelling, queues, and the boring-but-load-bearing infrastructure that keeps real software running in production.',
        accent: 'primary',
        query: 'backend',
        iconPath:
            'M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM3 16a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM7 6.5h.01M7 17.5h.01',
    },
    {
        title: 'Web',
        description:
            'The web platform itself — accessibility, performance, semantic HTML, and the standards that age better than any single framework.',
        accent: 'amber',
        query: 'web',
        iconPath:
            'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z',
    },
];

function chipClasses(accent: Accent): string {
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

function linkClasses(accent: Accent): string {
    const map: Record<Accent, string> = {
        amber: 'text-amber-700 hover:text-amber-600',
        primary: 'text-teal-700 hover:text-teal-600',
        secondary: 'text-violet-700 hover:text-violet-600',
        slate: 'text-slate-700 hover:text-slate-600',
        sky: 'text-sky-700 hover:text-sky-600',
        rose: 'text-rose-700 hover:text-rose-600',
    };

    return map[accent];
}
</script>

<template>
    <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" role="list">
        <li v-for="(pillar, index) in pillars" :key="pillar.query" v-reveal="index">
            <Link
                :href="blogIndex.url({ query: { q: pillar.query } })"
                class="group flex h-full flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:shadow-md focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                :class="cardHoverClasses(pillar.accent)"
            >
                <span
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl ring-1 ring-inset"
                    :class="chipClasses(pillar.accent)"
                    aria-hidden="true"
                >
                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path :d="pillar.iconPath" />
                    </svg>
                </span>
                <h3
                    class="mt-5 text-xl leading-snug font-bold text-slate-800 group-hover:text-teal-800"
                >
                    {{ pillar.title }}
                </h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">
                    {{ pillar.description }}
                </p>
                <span
                    class="mt-4 inline-flex items-center gap-1 text-sm font-semibold transition"
                    :class="linkClasses(pillar.accent)"
                >
                    Browse {{ pillar.title.toLowerCase() }} posts
                    <span aria-hidden="true">→</span>
                </span>
            </Link>
        </li>
    </ul>
</template>
