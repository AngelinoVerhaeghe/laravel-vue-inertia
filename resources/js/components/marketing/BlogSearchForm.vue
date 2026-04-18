<script setup lang="ts">
import { ref, watch } from 'vue';
import { index as blogIndex } from '@/routes/blog';

const props = withDefaults(
    defineProps<{
        initialQuery?: string;
        placeholder?: string;
        size?: 'md' | 'lg';
    }>(),
    {
        initialQuery: '',
        placeholder: 'Search the blog…',
        size: 'lg',
    },
);

const query = ref(props.initialQuery);

watch(
    () => props.initialQuery,
    (value) => {
        query.value = value;
    },
);
</script>

<template>
    <form
        :action="blogIndex.url()"
        method="get"
        role="search"
        :aria-label="placeholder"
        class="w-full"
    >
        <label for="blog-search-input" class="sr-only">Search posts</label>
        <div
            class="flex items-stretch gap-2 rounded-xl border border-slate-200/80 bg-white p-1.5 shadow-sm transition focus-within:border-teal-400 focus-within:ring-2 focus-within:ring-teal-400/40"
            :class="size === 'lg' ? 'max-w-2xl' : 'max-w-xl'"
        >
            <span
                class="flex items-center pl-2 text-slate-400"
                aria-hidden="true"
            >
                <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </span>
            <input
                id="blog-search-input"
                v-model="query"
                type="search"
                name="q"
                autocomplete="off"
                spellcheck="false"
                :placeholder="placeholder"
                class="min-w-0 flex-1 bg-transparent px-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none sm:text-base"
                maxlength="100"
            />
            <button
                type="submit"
                class="inline-flex shrink-0 cursor-pointer items-center justify-center rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-500 focus-visible:ring-2 focus-visible:ring-teal-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
            >
                Search
            </button>
        </div>
    </form>
</template>
