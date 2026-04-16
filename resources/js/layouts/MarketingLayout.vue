<script setup lang="ts">
import FlashToast from '@/components/FlashToast.vue';
import MarketingFooter from '@/components/layout/MarketingFooter.vue';
import MarketingGlow from '@/components/layout/MarketingGlow.vue';
import MarketingHeader from '@/components/layout/MarketingHeader.vue';

defineProps<{
    activeNav?: 'home' | 'blog' | 'contact' | 'newsletter';
}>();
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col bg-linear-to-b from-slate-50 via-white to-amber-50/40 text-slate-600 antialiased"
    >
        <FlashToast />
        <MarketingHeader :active-nav="activeNav" />
        <main class="relative flex-1">
            <MarketingGlow />
            <div class="relative z-10">
                <slot />
            </div>
        </main>
        <MarketingFooter />
    </div>
</template>
