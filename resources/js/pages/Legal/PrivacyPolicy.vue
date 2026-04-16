<script setup lang="ts">
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Link } from '@inertiajs/vue3';
import { cookies } from '@/routes/legal';

const lastUpdated = 'April 16, 2026';
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Privacy Policy — Stack Notes',
            description:
                'How Stack Notes collects, uses, and protects your personal data, including newsletter subscriptions and analytics.',
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
                    Privacy Policy
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
                        1. Introduction
                    </h2>
                    <p>
                        Stack Notes (“we”, “us”, or “our”) operates this website.
                        This Privacy Policy explains how we collect, use,
                        disclose, and safeguard your information when you visit
                        our site. Please read it carefully. If you do not agree
                        with this policy, please do not use the website.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        2. Information we collect
                    </h2>
                    <p>We may collect information in the following ways:</p>
                    <ul class="list-inside list-disc space-y-2 pl-1">
                        <li>
                            <strong class="text-slate-700"
                                >Information you provide</strong
                            >
                            — such as your name, email address, or message
                            content when you contact us, or your email address
                            when you sign up for our email newsletter (see
                            <a
                                href="#newsletter"
                                class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                                >Newsletter</a
                            >
                            below).
                        </li>
                        <li>
                            <strong class="text-slate-700"
                                >Automatically collected data</strong
                            >
                            — such as IP address, browser type, device
                            information, pages viewed, and approximate location
                            derived from IP, collected through logs and cookies
                            or similar technologies. We may also process limited
                            technical data for abuse prevention on public forms
                            (for example, rate limiting).
                        </li>
                    </ul>
                </section>

                <section id="newsletter" class="space-y-3 scroll-mt-24">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        3. Newsletter
                    </h2>
                    <p>
                        Stack Notes offers an optional email newsletter. This
                        section describes what we collect and how we use it.
                    </p>
                    <ul class="list-inside list-disc space-y-2 pl-1">
                        <li>
                            <strong class="text-slate-700">What we collect.</strong>
                            When you subscribe, we collect the email address you
                            submit. We do not ask for your name solely for the
                            newsletter.
                        </li>
                        <li>
                            <strong class="text-slate-700">Double opt-in.</strong>
                            We only add you to our active mailing list after you
                            confirm your subscription by clicking the link in
                            our confirmation email. Until you confirm, we treat
                            your signup as pending.
                        </li>
                        <li>
                            <strong class="text-slate-700"
                                >Confirmation links.</strong
                            >
                            Confirmation links expire after a limited time. If
                            yours expires, you can request a new confirmation
                            email or subscribe again using the same address.
                        </li>
                        <li>
                            <strong class="text-slate-700">What we send.</strong>
                            We send occasional editorial emails (for example,
                            roughly once per month) about Stack Notes and related
                            topics. You will not receive routine marketing
                            messages from us until you have confirmed.
                        </li>
                        <li>
                            <strong class="text-slate-700">Unsubscribe.</strong>
                            Every newsletter email includes an unsubscribe link.
                            You may also stop receiving the newsletter by
                            contacting us. If you unsubscribe, we keep your
                            email only as needed to honor your choice and meet
                            legal or operational requirements, as described under
                            Data retention.
                        </li>
                        <li>
                            <strong class="text-slate-700">Former subscribers.</strong>
                            If you previously unsubscribed, you may sign up again
                            with the same email address through the newsletter
                            form; we will run the same confirmation process.
                        </li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        4. How we use your information
                    </h2>
                    <p>We may use the information we collect to:</p>
                    <ul class="list-inside list-disc space-y-2 pl-1">
                        <li>Operate, maintain, and improve the website;</li>
                        <li>Respond to your inquiries and provide support;</li>
                        <li>
                            Send our email newsletter to subscribers who have
                            completed the confirmation step described above;
                        </li>
                        <li>
                            Send administrative or transactional messages
                            (e.g. service announcements);
                        </li>
                        <li>
                            Analyze usage trends and improve content and user
                            experience;
                        </li>
                        <li>
                            Detect, prevent, and address technical or security
                            issues, including abuse of public forms.
                        </li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        5. Cookies and similar technologies
                    </h2>
                    <p>
                        We may use cookies and similar technologies to recognize
                        your browser, remember preferences, and understand how
                        the site is used. For more detail, see our
                        <Link
                            :href="cookies.url()"
                            class="font-medium text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                            >Cookie Policy</Link
                        >.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        6. Sharing of information
                    </h2>
                    <p>
                        We do not sell your personal information. We may share
                        information with service providers who assist us in
                        operating the website (e.g. hosting, analytics, email
                        delivery), subject to appropriate safeguards. We may also
                        disclose information if required by law or to protect our
                        rights and the safety of users.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        7. Data retention
                    </h2>
                    <p>
                        We retain information only as long as necessary for the
                        purposes described in this policy, unless a longer
                        retention period is required or permitted by law. For
                        newsletter subscribers, we retain your email and related
                        subscription status (for example, whether you have
                        confirmed, unsubscribed, or are still pending) as needed
                        to manage your preferences and send (or not send) email
                        in line with this policy.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        8. Security
                    </h2>
                    <p>
                        We implement reasonable technical and organizational
                        measures to protect your information. No method of
                        transmission over the Internet is completely secure; we
                        cannot guarantee absolute security.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        9. Your rights
                    </h2>
                    <p>
                        Depending on where you live, you may have rights to
                        access, correct, delete, or restrict processing of your
                        personal data, or to object to certain processing. To
                        exercise these rights, contact us using the details
                        below. We may need to verify your identity before
                        responding.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        10. Children’s privacy
                    </h2>
                    <p>
                        The website is not directed at children under 16. We do
                        not knowingly collect personal information from children.
                        If you believe we have collected such information,
                        please contact us so we can delete it.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        11. International visitors
                    </h2>
                    <p>
                        If you access the site from outside the country where our
                        servers or service providers are located, your
                        information may be transferred to and processed in other
                        jurisdictions. By using the site, you consent to such
                        transfers where permitted by law.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        12. Changes to this policy
                    </h2>
                    <p>
                        We may update this Privacy Policy from time to time. We
                        will post the revised version on this page and update
                        the “Last updated” date. Continued use of the site after
                        changes constitutes acceptance of the updated policy.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="font-heading text-lg font-bold text-slate-800">
                        13. Contact us
                    </h2>
                    <p>
                        If you have questions about this Privacy Policy, please
                        contact us at
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
