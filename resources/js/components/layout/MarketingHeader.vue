<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { contact, home, newsletter } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
import { ref, watchEffect } from 'vue';

defineProps<{
    activeNav?: 'home' | 'blog' | 'contact' | 'newsletter';
}>();

const menuOpen = ref(false);

watchEffect(() => {
    document.body.style.overflow = menuOpen.value ? 'hidden' : '';
});

const navClass = (active: boolean) =>
    [
        'rounded-lg px-3 py-2 text-sm font-medium transition',
        active
            ? 'bg-teal-50 text-teal-800'
            : 'text-slate-600 hover:bg-slate-100 hover:text-teal-700',
    ].join(' ');

const socialLinks = [
    { network: 'github',   href: 'https://github.com/',    label: 'GitHub'   },
    { network: 'x',        href: 'https://x.com/',         label: 'X'        },
    { network: 'linkedin', href: 'https://www.linkedin.com/', label: 'LinkedIn' },
    { network: 'discord',  href: 'https://discord.com/',   label: 'Discord'  },
    { network: 'youtube',  href: 'https://www.youtube.com/', label: 'YouTube' },
];
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/75 backdrop-blur-md"
    >
        <div
            class="mx-auto flex max-w-6xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8"
        >
            <!-- Logo -->
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

            <!-- Desktop nav -->
            <nav
                class="hidden flex-1 items-center justify-center gap-1 md:flex"
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

            <!-- Hamburger button -->
            <button
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-teal-700 md:hidden"
                :aria-expanded="menuOpen"
                aria-label="Toggle menu"
                @click="menuOpen = !menuOpen"
            >
                <svg
                    v-if="!menuOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile menu overlay -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="menuOpen"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden"
            aria-hidden="true"
            @click="menuOpen = false"
        />
    </Transition>

    <!-- Mobile menu panel -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <div
            v-if="menuOpen"
            class="fixed top-0 right-0 bottom-0 z-50 flex w-72 flex-col bg-white shadow-2xl md:hidden"
        >
            <!-- Panel header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <Link
                    :href="home.url()"
                    class="group flex items-center gap-2 font-heading text-base font-semibold tracking-tight text-slate-800"
                    @click="menuOpen = false"
                >
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-amber-400 to-amber-600 text-xs font-bold text-white shadow-sm ring-1 ring-amber-500/20"
                        >SN</span
                    >
                    <span>Stack Notes</span>
                </Link>
                <button
                    type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Close menu"
                    @click="menuOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Nav links -->
            <nav class="flex-1 overflow-y-auto px-4 py-5" aria-label="Mobile navigation">
                <p class="mb-2 px-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">
                    Navigate
                </p>
                <ul class="space-y-1">
                    <li>
                        <Link
                            :href="home.url()"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                activeNav === 'home'
                                    ? 'bg-teal-50 text-teal-800'
                                    : 'text-slate-700 hover:bg-slate-50 hover:text-teal-700',
                            ]"
                            @click="menuOpen = false"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            </span>
                            Home
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="blogIndex.url()"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                activeNav === 'blog'
                                    ? 'bg-teal-50 text-teal-800'
                                    : 'text-slate-700 hover:bg-slate-50 hover:text-teal-700',
                            ]"
                            @click="menuOpen = false"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </span>
                            Blog
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="newsletter.url()"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                activeNav === 'newsletter'
                                    ? 'bg-teal-50 text-teal-800'
                                    : 'text-slate-700 hover:bg-slate-50 hover:text-teal-700',
                            ]"
                            @click="menuOpen = false"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </span>
                            Newsletter
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="contact.url()"
                            :class="[
                                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                activeNav === 'contact'
                                    ? 'bg-teal-50 text-teal-800'
                                    : 'text-slate-700 hover:bg-slate-50 hover:text-teal-700',
                            ]"
                            @click="menuOpen = false"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            </span>
                            Contact
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- Social links footer -->
            <div class="border-t border-slate-100 bg-slate-50/80 px-5 py-4">
                <p class="mb-3 text-xs font-semibold tracking-wider text-slate-400 uppercase">
                    Follow us
                </p>
                <ul class="flex items-center gap-1" role="list" aria-label="Social links">
                    <li v-for="link in socialLinks" :key="link.network">
                        <a
                            :href="link.href"
                            :aria-label="link.label"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-200 hover:text-teal-700"
                        >
                            <!-- GitHub -->
                            <svg v-if="link.network === 'github'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                            <!-- X -->
                            <svg v-else-if="link.network === 'x'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                            <!-- LinkedIn -->
                            <svg v-else-if="link.network === 'linkedin'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>
                            <!-- Discord -->
                            <svg v-else-if="link.network === 'discord'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z" /></svg>
                            <!-- YouTube -->
                            <svg v-else-if="link.network === 'youtube'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" /></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </Transition>
</template>
