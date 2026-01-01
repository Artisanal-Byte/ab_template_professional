<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

const attrs = useAttrs();

const skeletonClass = computed(() =>
    cn(
        'animate-pulse rounded-md bg-secondary/40',
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <div v-bind="boundAttrs" :class="skeletonClass" />
</template>
