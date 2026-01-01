<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() => props.class);
</script>

<template>
    <main
        v-if="props.variant === 'sidebar'"
        class="flex w-full flex-1 flex-col bg-background text-foreground"
        :class="className"
    >
        <slot />
    </main>
    <main
        v-else
        class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl"
        :class="className"
    >
        <slot />
    </main>
</template>
