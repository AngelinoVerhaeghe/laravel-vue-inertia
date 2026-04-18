<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { category as blogCategory } from '@/routes/blog';

type Accent = 'amber' | 'primary' | 'secondary' | 'slate' | 'sky' | 'rose';

interface Topic {
    name: string;
    slug: string;
    accent: Accent;
}

const topics: readonly Topic[] = [
    { name: 'Frontend', slug: 'frontend', accent: 'secondary' },
    { name: 'Backend', slug: 'backend', accent: 'amber' },
    { name: 'DevOps', slug: 'devops', accent: 'sky' },
    { name: 'Architecture', slug: 'architecture', accent: 'rose' },
    { name: 'Web', slug: 'web', accent: 'primary' },
] as const;

function tagClasses(accent: Accent): string {
    const map: Record<Accent, string> = {
        amber: 'bg-amber-100 text-amber-900 ring-amber-200/80 hover:bg-amber-200/80',
        primary: 'bg-teal-100 text-teal-900 ring-teal-200/80 hover:bg-teal-200/80',
        secondary: 'bg-violet-100 text-violet-900 ring-violet-200/80 hover:bg-violet-200/80',
        slate: 'bg-slate-100 text-slate-900 ring-slate-200/80 hover:bg-slate-200/80',
        sky: 'bg-sky-100 text-sky-900 ring-sky-200/80 hover:bg-sky-200/80',
        rose: 'bg-rose-100 text-rose-900 ring-rose-200/80 hover:bg-rose-200/80',
    };

    return map[accent];
}
</script>

<template>
    <nav aria-label="Browse posts by topic">
        <p
            class="mb-2 text-xs font-semibold tracking-wider text-slate-500 uppercase"
        >
            Browse by topic
        </p>
        <ul class="flex flex-wrap gap-2" role="list">
            <li v-for="topic in topics" :key="topic.slug">
                <Link
                    :href="blogCategory.url(topic.slug)"
                    class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold ring-1 ring-inset transition focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                    :class="tagClasses(topic.accent)"
                >
                    {{ topic.name }}
                </Link>
            </li>
        </ul>
    </nav>
</template>
