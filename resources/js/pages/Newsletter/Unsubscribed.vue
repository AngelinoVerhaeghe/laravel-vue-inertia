<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SeoHead from '@/components/SeoHead.vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { home } from '@/routes';

defineProps<{
    alreadyUnsubscribed: boolean;
}>();
</script>

<template>
    <SeoHead
        :seo="{
            title: 'Unsubscribed — Stack Notes',
            description: 'You have been unsubscribed from the Stack Notes newsletter.',
            noindex: true,
        }"
    />

    <MarketingLayout>
        <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-8 shadow-sm"
            >
                <h1
                    class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl"
                >
                    {{
                        alreadyUnsubscribed
                            ? 'You were already unsubscribed'
                            : 'You are unsubscribed'
                    }}
                </h1>
                <p class="mt-4 text-slate-600">
                    {{
                        alreadyUnsubscribed
                            ? 'This email address was not on our active list anymore. No further action is needed.'
                            : 'We removed your email from the Stack Notes newsletter list. You will not receive future issues from this signup.'
                    }}
                </p>
                <p class="mt-6">
                    <Link
                        :href="home.url()"
                        class="font-semibold text-teal-700 underline decoration-teal-600/30 underline-offset-2 hover:decoration-teal-600"
                    >
                        Back to home
                    </Link>
                </p>
            </div>
        </section>
    </MarketingLayout>
</template>
