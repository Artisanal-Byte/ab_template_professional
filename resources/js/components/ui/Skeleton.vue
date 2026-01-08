<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const skeletonClass = computed(() =>
    cn(
        'animate-pulse rounded-md bg-secondary-subtle',
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <div v-bind="boundAttrs" :class="skeletonClass" />
</template>

