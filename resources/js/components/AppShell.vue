<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps({
    variant: {
        type: String,
        default: 'sidebar',
    },
});

const wrapperClass = computed(() =>
    props.variant === 'header'
        ? 'flex min-h-screen w-full flex-col'
        : 'flex min-h-screen w-full',
);
</script>

<template>
    <div :class="wrapperClass">
        <slot />
    </div>
</template>
