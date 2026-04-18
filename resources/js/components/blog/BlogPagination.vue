<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

export interface PaginationLink {
    page: number;
    url: string;
    active: boolean;
}

export interface PaginationPayload {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    prevUrl: string | null;
    nextUrl: string | null;
    links: PaginationLink[];
}

const props = defineProps<{
    pagination: PaginationPayload;
}>();

const visibleLinks = computed<(PaginationLink | { ellipsis: true; key: string })[]>(() => {
    const { currentPage, lastPage, links } = props.pagination;

    if (lastPage <= 7) {
        return links;
    }

    const window = new Set<number>([
        1,
        lastPage,
        currentPage - 1,
        currentPage,
        currentPage + 1,
    ]);

    const pages = Array.from(window)
        .filter((page) => page >= 1 && page <= lastPage)
        .sort((a, b) => a - b);

    const result: (PaginationLink | { ellipsis: true; key: string })[] = [];
    let previous = 0;

    pages.forEach((page) => {
        if (previous && page - previous > 1) {
            result.push({ ellipsis: true, key: `gap-${previous}-${page}` });
        }

        const link = links.find((l) => l.page === page);

        if (link) {
            result.push(link);
        }

        previous = page;
    });

    return result;
});
</script>

<template>
    <nav
        v-if="pagination.lastPage > 1"
        class="mt-10 flex items-center justify-between gap-4 border-t border-slate-200/80 pt-6"
        aria-label="Pagination"
    >
        <Link
            v-if="pagination.prevUrl"
            :href="pagination.prevUrl"
            class="inline-flex items-center gap-1 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-teal-300 hover:text-teal-800"
            rel="prev"
        >
            ← Previous
        </Link>
        <span
            v-else
            class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-400"
            aria-disabled="true"
        >
            ← Previous
        </span>

        <ul
            class="hidden items-center gap-1 text-sm font-medium sm:flex"
            role="list"
        >
            <li
                v-for="item in visibleLinks"
                :key="'ellipsis' in item ? item.key : `page-${item.page}`"
            >
                <span
                    v-if="'ellipsis' in item"
                    class="px-2 text-slate-400"
                    aria-hidden="true"
                >
                    …
                </span>
                <Link
                    v-else-if="!item.active"
                    :href="item.url"
                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-transparent px-3 text-slate-600 transition hover:border-slate-300 hover:bg-white hover:text-teal-800"
                >
                    {{ item.page }}
                </Link>
                <span
                    v-else
                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-teal-300 bg-teal-50 px-3 font-semibold text-teal-800"
                    aria-current="page"
                >
                    {{ item.page }}
                </span>
            </li>
        </ul>

        <p class="text-sm text-slate-500 sm:hidden">
            Page {{ pagination.currentPage }} of {{ pagination.lastPage }}
        </p>

        <Link
            v-if="pagination.nextUrl"
            :href="pagination.nextUrl"
            class="inline-flex items-center gap-1 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-teal-300 hover:text-teal-800"
            rel="next"
        >
            Next →
        </Link>
        <span
            v-else
            class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-400"
            aria-disabled="true"
        >
            Next →
        </span>
    </nav>
</template>
