<script setup lang="ts">
import MarketingFooter from '@/components/layout/MarketingFooter.vue';
import MarketingHeader from '@/components/layout/MarketingHeader.vue';

defineProps<{
    activeNav?: 'home' | 'blog' | 'contact' | 'newsletter';
}>();
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-linear-to-b from-slate-50 via-white to-amber-50/40 text-slate-600 antialiased"
    >
        <MarketingHeader :active-nav="activeNav" />
        <main class="flex-1">
            <slot />
        </main>
        <MarketingFooter />
    </div>
</template>
