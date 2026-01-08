<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel({
    default: false,
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const checkboxClass = computed(() =>
    cn(
        'size-4 rounded border border-border bg-background text-primary accent-[var(--primary)] transition-colors outline-none focus-visible:ring-2 focus-visible:ring-focus-ring disabled:cursor-not-allowed disabled:opacity-50',
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <input
        v-model="model"
        type="checkbox"
        v-bind="boundAttrs"
        :class="checkboxClass"
    />
</template>

