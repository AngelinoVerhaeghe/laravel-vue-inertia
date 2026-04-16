<script setup lang="ts">
type Glow = {
    class: string;
};

withDefaults(
    defineProps<{
        glows?: Glow[];
    }>(),
    {
        glows: () => [
            {
                class: 'bg-amber-200/22 -top-32 -right-44 h-[26rem] w-[26rem] blur-3xl',
            },
            {
                class: 'bg-teal-200/18 top-1/3 -left-44 h-[24rem] w-[24rem] blur-3xl',
            },
            {
                class: 'bg-violet-300/22 bottom-28 -right-20 h-[26rem] w-[26rem] blur-3xl',
            },
            {
                class: 'bg-violet-200/16 -bottom-52 right-1/4 h-[30rem] w-[30rem] blur-3xl',
            },
        ],
    },
);
</script>

<template>
    <div
        class="pointer-events-none absolute inset-0 z-0 overflow-hidden"
        aria-hidden="true"
    >
        <div
            v-for="(glow, index) in glows"
            :key="index"
            class="absolute rounded-full"
            :class="glow.class"
        />
    </div>
</template>

