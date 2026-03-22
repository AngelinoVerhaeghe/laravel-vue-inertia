<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { contact, home, newsletter } from '@/routes';
import { index as blogIndex } from '@/routes/blog';

defineProps<{
    activeNav?: 'home' | 'blog' | 'contact' | 'newsletter';
}>();

const navClass = (active: boolean) =>
    [
        'rounded-lg px-3 py-2 text-sm font-medium transition',
        active
            ? 'bg-teal-50 text-teal-800'
            : 'text-slate-600 hover:bg-slate-100 hover:text-teal-700',
    ].join(' ');
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/75 backdrop-blur-md"
    >
        <div
            class="mx-auto flex max-w-6xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8"
        >
            <Link
                :href="home.url()"
                class="group flex items-center gap-2 font-heading text-lg font-semibold tracking-tight text-slate-800"
            >
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-linear-to-br from-amber-400 to-amber-600 text-sm font-bold text-white shadow-sm ring-1 ring-amber-500/20 transition group-hover:from-amber-500 group-hover:to-amber-700"
                    >SN</span
                >
                <span>Stack Notes</span>
            </Link>

            <nav
                class="hidden items-center gap-1 md:flex"
                aria-label="Main navigation"
            >
                <Link :href="home.url()" :class="navClass(activeNav === 'home')">
                    Home
                </Link>
                <Link
                    :href="blogIndex.url()"
                    :class="navClass(activeNav === 'blog')"
                >
                    Blog
                </Link>
                <Link
                    :href="contact.url()"
                    :class="navClass(activeNav === 'contact')"
                >
                    Contact
                </Link>
                <Link
                    :href="newsletter.url()"
                    :class="navClass(activeNav === 'newsletter')"
                >
                    Newsletter
                </Link>
            </nav>

            <div class="flex items-center gap-3">
                <a
                    href="#"
                    class="hidden rounded-lg border border-violet-200 bg-white px-3 py-2 text-sm font-medium text-violet-800 shadow-sm transition hover:border-violet-300 hover:bg-violet-50 sm:inline-flex"
                >
                    Sign in
                </a>
                <Link
                    :href="newsletter.url()"
                    class="rounded-lg bg-linear-to-r from-teal-600 to-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-teal-600/25 transition hover:from-teal-500 hover:to-teal-600"
                >
                    Subscribe
                </Link>
            </div>
        </div>
    </header>
</template>
