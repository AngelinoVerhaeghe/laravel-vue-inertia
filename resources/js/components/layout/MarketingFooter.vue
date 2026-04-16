<script setup lang="ts">
import SocialLinks from '@/components/SocialLinks.vue';
import type { SocialLinkItem } from '@/components/SocialLinks.vue';
import { Link } from '@inertiajs/vue3';
import { contact, home, newsletter } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
import { cookies, privacy, terms } from '@/routes/legal';

function isExternal(href: string): boolean {
    return href.startsWith('http');
}

/** Below `sm`: Explore → Community → Legal first; brand uses order-last. From `sm` up: natural column order. */
function columnOrderClass(index: number): string {
    const orders = [
        'order-1 sm:order-none',
        'order-2 sm:order-none',
        'order-3 sm:order-none',
    ] as const;

    return `${orders[index] ?? ''} sm:pl-4`.trim();
}

const socialLinks: SocialLinkItem[] = [
    { network: 'github', href: 'https://github.com/' },
    { network: 'x', href: 'https://x.com/' },
    { network: 'linkedin', href: 'https://www.linkedin.com/' },
    { network: 'discord', href: 'https://discord.com/' },
    { network: 'youtube', href: 'https://www.youtube.com/' },
];

const footerColumns = [
    {
        title: 'Explore',
        links: [
            { label: 'Home', href: home.url() },
            { label: 'All posts', href: blogIndex.url() },
            { label: 'Full-stack', href: blogIndex.url() },
            { label: 'Frontend', href: blogIndex.url() },
        ],
    },
    {
        title: 'Community',
        links: [
            { label: 'Discord', href: 'https://discord.com/' },
            { label: 'GitHub', href: 'https://github.com/' },
            { label: 'Contact', href: contact.url() },
        ],
    },
    {
        title: 'Legal',
        links: [
            { label: 'Privacy', href: privacy.url() },
            { label: 'Terms', href: terms.url() },
            { label: 'Cookies', href: cookies.url() },
        ],
    },
];
</script>

<template>
    <footer class="border-t border-slate-200 bg-slate-900 text-slate-400">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div
                class="mb-10 flex flex-col items-start justify-between gap-6 rounded-2xl border border-slate-700/60 bg-slate-800/60 px-6 py-6 sm:flex-row sm:items-center lg:px-8"
            >
                <div>
                    <p class="font-heading text-base font-semibold text-white">
                        Stay in the loop
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        One short monthly email on full-stack craft. No spam,
                        unsubscribe any time.
                    </p>
                </div>
                <Link
                    :href="newsletter.url()"
                    class="shrink-0 rounded-xl bg-linear-to-r from-teal-500 to-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-teal-900/30 transition hover:from-teal-400 hover:to-teal-500"
                >
                    Subscribe for free
                </Link>
            </div>
            <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-4 md:gap-8">
                <div class="order-last sm:order-none">
                    <Link :href="home.url()" class="inline-flex items-center">
                        <img
                            src="/images/stack-notes-footer-logo.png"
                            alt="Stack Notes"
                            class="w-[160px] h-auto"
                            loading="lazy"
                        />
                    </Link>
                    <p class="mt-3 text-sm leading-relaxed">
                        Field notes from the stack: frontend craft, API design,
                        databases, and the glue that holds production apps
                        together—written for developers who ship and refactor in
                        the real world.
                    </p>
                    <SocialLinks
                        :links="socialLinks"
                        variant="footer"
                        class="mt-5"
                    />
                    <p class="mt-6 text-xs text-slate-500">
                        © {{ new Date().getFullYear() }} Stack Notes.
                    </p>
                </div>
                <div
                    v-for="(col, idx) in footerColumns"
                    :key="col.title"
                    :class="columnOrderClass(idx)"
                >
                    <p
                        class="font-heading text-sm font-semibold tracking-wider text-slate-300 uppercase"
                    >
                        {{ col.title }}
                    </p>
                    <ul class="mt-4 space-y-2" role="list">
                        <li v-for="link in col.links" :key="link.label">
                            <Link
                                v-if="!isExternal(link.href)"
                                :href="link.href"
                                class="text-sm transition hover:text-amber-400"
                            >
                                {{ link.label }}
                            </Link>
                            <a
                                v-else
                                :href="link.href"
                                class="text-sm transition hover:text-amber-400"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {{ link.label }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</template>
