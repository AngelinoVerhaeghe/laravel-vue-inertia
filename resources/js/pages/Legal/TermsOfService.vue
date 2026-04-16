<script setup lang="ts">
import { create as newsletterPage } from '@/actions/App/Http/Controllers/NewsletterSubscriptionController';
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Link } from '@inertiajs/vue3';
import { privacy } from '@/routes/legal';

const lastUpdated = 'April 16, 2026';
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Terms of Service — Stack Notes',
            description:
                'The terms that govern your use of Stack Notes, including acceptable use, liability, and content licensing.',
        }"
    />

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
                    Terms of Service
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
                        1. Agreement to terms
                    </h2>
                    <p>
                        These Terms of Service (“Terms”) govern your access to
                        and use of the Stack Notes website and any related
                        services (collectively, the “Service”). By accessing or
                        using the Service, you agree to be bound by these Terms.
                        If you disagree with any part of the Terms, you may not
                        access the Service.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        2. Intellectual property rights
                    </h2>
                    <p>
                        Unless otherwise indicated, the Service and its original
                        content, features, and functionality are owned by Stack
                        Notes and are protected by copyright, trademark, and
                        other intellectual property laws. You may not copy,
                        modify, distribute, sell, or lease any part of the
                        Service without our prior written consent, except as
                        permitted by law or expressly allowed for normal
                        browsing and sharing of links.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        3. User conduct
                    </h2>
                    <p>You agree not to:</p>
                    <ul class="list-inside list-disc space-y-2 pl-1">
                        <li>
                            Use the Service in any way that violates applicable
                            laws or regulations;
                        </li>
                        <li>
                            Attempt to gain unauthorized access to the Service,
                            other accounts, or related systems or networks;
                        </li>
                        <li>
                            Interfere with or disrupt the Service or servers or
                            networks connected to the Service;
                        </li>
                        <li>
                            Use automated means (e.g. scrapers, bots) to access
                            the Service in a manner that imposes an unreasonable
                            load on our infrastructure, without permission;
                        </li>
                        <li>
                            Transmit viruses, malware, or other harmful code.
                        </li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        4. User content
                    </h2>
                    <p>
                        If you submit comments, feedback, or other content to us,
                        you grant us a non-exclusive, worldwide, royalty-free
                        license to use, reproduce, modify, and display such
                        content for the purpose of operating and promoting the
                        Service. You represent that you have the rights to grant
                        this license and that your content does not infringe
                        third-party rights.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        5. Email newsletter
                    </h2>
                    <p>
                        Stack Notes may offer an optional email newsletter. If you
                        sign up, you agree that we may use the email address you
                        provide to send you the messages described at the point of
                        signup, subject to our
                        <Link
                            :href="`${privacy.url()}#newsletter`"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >Privacy Policy</Link
                        >
                        (including confirmation, unsubscribe, and retention). We
                        only send routine editorial/marketing messages after you
                        complete any confirmation step we require. You may
                        unsubscribe or resubscribe as described there and on our
                        <Link
                            :href="newsletterPage.url()"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >newsletter</Link
                        >
                        pages.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        6. Third-party links
                    </h2>
                    <p>
                        The Service may contain links to third-party websites or
                        services that are not owned or controlled by Stack Notes.
                        We have no control over and assume no responsibility for
                        the content, privacy policies, or practices of any
                        third-party sites. You access third-party links at your
                        own risk.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        7. Disclaimer of warranties
                    </h2>
                    <p>
                        THE SERVICE IS PROVIDED ON AN “AS IS” AND “AS
                        AVAILABLE” BASIS, WITHOUT WARRANTIES OF ANY KIND,
                        WHETHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
                        IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
                        PARTICULAR PURPOSE, OR NON-INFRINGEMENT. WE DO NOT
                        WARRANT THAT THE SERVICE WILL BE UNINTERRUPTED, SECURE,
                        OR ERROR-FREE, OR THAT DEFECTS WILL BE CORRECTED.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        8. Limitation of liability
                    </h2>
                    <p>
                        TO THE MAXIMUM EXTENT PERMITTED BY LAW, IN NO EVENT SHALL
                        STACK NOTES OR ITS SUPPLIERS BE LIABLE FOR ANY INDIRECT,
                        INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES,
                        OR ANY LOSS OF PROFITS OR DATA, ARISING OUT OF OR IN
                        CONNECTION WITH YOUR USE OF THE SERVICE, WHETHER BASED
                        ON WARRANTY, CONTRACT, TORT, OR ANY OTHER LEGAL
                        THEORY, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
                        DAMAGES. OUR TOTAL LIABILITY FOR ANY CLAIM ARISING FROM
                        THE SERVICE SHALL NOT EXCEED THE AMOUNT YOU PAID US, IF
                        ANY, IN THE TWELVE (12) MONTHS PRECEDING THE CLAIM, OR
                        ONE HUNDRED US DOLLARS (USD $100), WHICHEVER IS GREATER.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        9. Indemnification
                    </h2>
                    <p>
                        You agree to defend, indemnify, and hold harmless Stack
                        Notes and its officers, directors, employees, and agents
                        from and against any claims, damages, obligations,
                        losses, liabilities, costs, or debt, and expenses
                        (including reasonable attorneys’ fees) arising from your
                        use of the Service or violation of these Terms.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        10. Termination
                    </h2>
                    <p>
                        We may suspend or terminate your access to the Service at
                        any time, with or without cause or notice. Provisions of
                        these Terms that by their nature should survive
                        termination shall survive, including ownership,
                        disclaimers, limitation of liability, and indemnification.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        11. Governing law
                    </h2>
                    <p>
                        These Terms shall be governed by and construed in
                        accordance with the laws applicable to the operator of
                        the Service, without regard to conflict of law
                        principles.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        12. Changes to terms
                    </h2>
                    <p>
                        We reserve the right to modify these Terms at any time.
                        We will post the updated Terms on this page and update
                        the “Last updated” date. Your continued use of the
                        Service after changes constitutes acceptance of the new
                        Terms.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        13. Contact
                    </h2>
                    <p>
                        For questions about these Terms, contact us at
                        <a
                            href="mailto:legal@stacknotes.test"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >legal@stacknotes.test</a
                        >
                        (replace with your real contact details).
                    </p>
                </section>
            </div>
        </article>
    </MarketingLayout>
</template>
