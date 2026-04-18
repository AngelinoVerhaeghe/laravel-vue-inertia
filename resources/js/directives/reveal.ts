import { useIntersectionObserver } from '@vueuse/core';
import type { Directive, DirectiveBinding } from 'vue';

type RevealDirection = 'up' | 'down' | 'left' | 'right' | 'fade';

const DIRECTIONS: ReadonlyArray<RevealDirection> = [
    'up',
    'down',
    'left',
    'right',
    'fade',
];

const MAX_DELAY_INDEX = 6;

const STOP_KEY = Symbol('reveal:stop');

type RevealElement = HTMLElement & {
    [STOP_KEY]?: () => void;
};

function resolveDirection(
    binding: DirectiveBinding<unknown>,
): RevealDirection {
    for (const direction of DIRECTIONS) {
        if (binding.modifiers[direction]) {
            return direction;
        }
    }

    return 'up';
}

function resolveDelay(binding: DirectiveBinding<unknown>): number {
    const raw = binding.value ?? binding.arg;
    const parsed = typeof raw === 'number' ? raw : Number.parseInt(String(raw ?? ''), 10);

    if (!Number.isFinite(parsed) || parsed <= 0) {
        return 0;
    }

    return Math.min(Math.trunc(parsed), MAX_DELAY_INDEX);
}

function prefersReducedMotion(): boolean {
    if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') {
        return false;
    }

    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export const vReveal: Directive<RevealElement, number | string | undefined> = {
    mounted(el, binding) {
        el.dataset.revealDirection = resolveDirection(binding);

        const delay = resolveDelay(binding);

        if (delay > 0) {
            el.dataset.revealDelay = String(delay);
        }

        if (prefersReducedMotion()) {
            el.dataset.reveal = 'visible';

            return;
        }

        el.dataset.reveal = 'hidden';

        const { stop } = useIntersectionObserver(
            el,
            ([entry]) => {
                if (!entry?.isIntersecting) {
                    return;
                }

                el.dataset.reveal = 'visible';
                stop();
            },
            {
                threshold: 0.15,
                rootMargin: '0px 0px -10% 0px',
            },
        );

        el[STOP_KEY] = stop;
    },
    unmounted(el) {
        el[STOP_KEY]?.();
        delete el[STOP_KEY];
    },
};
