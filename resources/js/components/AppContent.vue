<script setup lang="ts">
import { computed } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    variant: {
        type: String,
        default: 'sidebar',
    },
    class: {
        type: String,
        default: '',
    },
});

const className = computed(() => props.class);
const contentClass = computed(() =>
    cn(
        props.variant === 'sidebar'
            ? 'flex w-full flex-1 flex-col bg-background text-foreground'
            : 'mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl',
        className.value,
    ),
);
</script>

<template>
    <main :class="contentClass">
        <slot />
    </main>
</template>
