<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();

const open = ref(false);
const message = ref('');
const variant = ref<'success' | 'info' | 'error'>('success');
const toastKey = ref(0);

/** Toast visibility and timer bar length (keep in sync with CSS `toast-timer`). */
const TOAST_MS = 5000;

type FlashPayload =
    | { text: string; variant: 'success' | 'info' | 'error' }
    | null;

const flashMessage = computed((): FlashPayload => {
    const f = page.props.flash;

    if (typeof f?.success === 'string' && f.success.length > 0) {
        return { text: f.success, variant: 'success' };
    }

    if (typeof f?.error === 'string' && f.error.length > 0) {
        return { text: f.error, variant: 'error' };
    }

    if (typeof f?.info === 'string' && f.info.length > 0) {
        return { text: f.info, variant: 'info' };
    }

    return null;
});

let hideTimer: ReturnType<typeof setTimeout> | undefined;

watch(
    flashMessage,
    (payload) => {
        if (hideTimer !== undefined) {
            clearTimeout(hideTimer);
            hideTimer = undefined;
        }

        if (payload !== null) {
            message.value = payload.text;
            variant.value = payload.variant;
            toastKey.value += 1;
            open.value = true;
            hideTimer = setTimeout(() => {
                open.value = false;
            }, TOAST_MS);
        } else {
            open.value = false;
        }
    },
    { immediate: true },
);

const palette = computed(() => {
    if (variant.value === 'info') {
        return {
            borderRing: 'border-violet-600/35 ring-violet-500/25 shadow-violet-900/15',
            bg: 'from-violet-100/95 via-white to-slate-50/90 text-violet-950',
            cardBorder: 'border-violet-800/10',
            label: 'text-violet-800',
            bar: 'bg-violet-600',
            labelText: 'Notice',
        };
    }
    if (variant.value === 'error') {
        return {
            borderRing: 'border-rose-600/35 ring-rose-500/25 shadow-rose-900/15',
            bg: 'from-rose-100/95 via-white to-slate-50/90 text-rose-950',
            cardBorder: 'border-rose-800/10',
            label: 'text-rose-800',
            bar: 'bg-rose-600',
            labelText: 'Error',
        };
    }

    return {
        borderRing: 'border-teal-600/35 ring-teal-500/25 shadow-teal-900/15',
        bg: 'from-teal-100/95 via-white to-amber-50/90 text-teal-950',
        cardBorder: 'border-teal-700/10',
        label: 'text-teal-800',
        bar: 'bg-teal-600',
        labelText: 'Success',
    };
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="translate-y-3 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-if="open"
                class="pointer-events-none fixed bottom-6 left-1/2 z-50 w-[min(100%-2rem,28rem)] -translate-x-1/2 px-4 sm:right-6 sm:left-auto sm:translate-x-0"
                role="status"
                :aria-live="variant === 'error' ? 'assertive' : 'polite'"
            >
                <div
                    class="pointer-events-auto overflow-hidden rounded-xl border-2 shadow-xl ring-2 backdrop-blur-sm"
                    :class="[palette.borderRing, 'bg-linear-to-br', palette.bg]"
                >
                    <div
                        class="border-b px-4 py-1.5"
                        :class="palette.cardBorder"
                    >
                        <p
                            class="text-xs font-bold tracking-wide uppercase"
                            :class="palette.label"
                        >
                            {{ palette.labelText }}
                        </p>
                    </div>
                    <p
                        class="px-4 pt-2 pb-3 text-sm leading-relaxed font-medium"
                    >
                        {{ message }}
                    </p>
                    <div
                        class="h-1.5 w-full bg-current opacity-15"
                        aria-hidden="true"
                    >
                        <div
                            :key="toastKey"
                            class="timer-bar h-full"
                            :class="palette.bar"
                        />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.timer-bar {
    width: 100%;
    transform-origin: left center;
    animation: toast-timer 5s linear forwards;
}

@keyframes toast-timer {
    from {
        transform: scaleX(1);
    }

    to {
        transform: scaleX(0);
    }
}
</style>
