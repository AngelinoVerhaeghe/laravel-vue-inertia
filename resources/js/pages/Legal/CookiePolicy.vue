<script setup lang="ts">
import { create as newsletterPage } from '@/actions/App/Http/Controllers/NewsletterSubscriptionController';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { privacy } from '@/routes/legal';

const lastUpdated = 'April 16, 2026';
</script>

<template>
    <Head title="Cookie Policy — Stack Notes" />

    <MarketingLayout>
        <article
            class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8"
        >
            <header class="border-b border-slate-200/80 pb-8">
                <p
                    class="text-xs font-semibold tracking-wider text-teal-700 uppercase"
                >
                    Legal
                </p>
                <h1
                    class="mt-2 text-3xl font-bold tracking-tight text-slate-800 sm:text-4xl"
                >
                    Cookie Policy
                </h1>
                <p class="mt-3 text-sm text-slate-500">
                    Last updated: {{ lastUpdated }}
                </p>
            </header>

            <div
                class="mt-10 space-y-10 text-sm leading-relaxed text-slate-600 sm:text-base"
            >
                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        1. What are cookies?
                    </h2>
                    <p>
                        Cookies are small text files stored on your device when
                        you visit a website. They are widely used to make sites
                        work more efficiently, remember preferences, and
                        understand how visitors use a site. Similar technologies
                        include local storage and pixel tags.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        2. How we use cookies
                    </h2>
                    <p>
                        Stack Notes may use cookies and similar technologies for
                        the following purposes:
                    </p>
                    <ul class="list-inside list-disc space-y-2 pl-1">
                        <li>
                            <strong class="text-slate-700">Essential</strong> —
                            required for core functionality such as security,
                            load balancing, and session management (including
                            form protection on pages such as the
                            <Link
                                :href="newsletterPage.url()"
                                class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                                >newsletter</Link
                            >
                            signup);
                        </li>
                        <li>
                            <strong class="text-slate-700">Preferences</strong> —
                            to remember choices you make (e.g. language or display
                            options);
                        </li>
                        <li>
                            <strong class="text-slate-700">Analytics</strong> —
                            to understand how visitors interact with the site
                            (e.g. pages viewed, approximate location, device type);
                        </li>
                        <li>
                            <strong class="text-slate-700">Performance</strong> —
                            to measure errors and improve speed and reliability.
                        </li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        3. Types of cookies we may use
                    </h2>
                    <p>
                        <strong class="text-slate-700">First-party cookies</strong>
                        are set by Stack Notes. <strong class="text-slate-700"
                            >Third-party cookies</strong
                        >
                        are set by partners such as analytics or embedded
                        content providers. Session cookies expire when you close
                        your browser; persistent cookies remain for a defined
                        period unless you delete them.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        4. Your choices
                    </h2>
                    <p>
                        Most browsers let you refuse or delete cookies through
                        settings. If you disable cookies, some features of the
                        site may not work correctly. You can also use industry
                        opt-out tools where available (e.g. for certain analytics
                        providers).
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        5. Do Not Track
                    </h2>
                    <p>
                        Some browsers transmit “Do Not Track” signals. There is
                        no consistent industry standard for how to respond; we
                        currently do not alter our practices in response to DNT
                        signals.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        6. Updates
                    </h2>
                    <p>
                        We may update this Cookie Policy to reflect changes in
                        our practices or legal requirements. The “Last updated”
                        date at the top indicates when it was last revised.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        7. More information
                    </h2>
                    <p>
                        For how we handle personal data more broadly—including
                        email addresses when you
                        <Link
                            :href="newsletterPage.url()"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >subscribe to our newsletter</Link
                        >
                        —see our
                        <Link
                            :href="`${privacy.url()}#newsletter`"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >Privacy Policy</Link
                        >
                        (section “Newsletter”). Questions about cookies can be
                        sent to
                        <a
                            href="mailto:privacy@stacknotes.test"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >privacy@stacknotes.test</a
                        >
                        (replace with your real contact details).
                    </p>
                </section>
            </div>
        </article>
    </MarketingLayout>
</template>
