<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

defineOptions({
    inheritAttrs: false,
});

const model = defineModel({
    type: [Boolean, Array],
    default: false,
});

const attrs = useAttrs();

const checkboxClass = computed(() =>
    cn(
        'size-4 rounded border border-border bg-background text-primary accent-[var(--primary)] transition-colors outline-none focus-visible:ring-2 focus-visible:ring-primary/30 disabled:cursor-not-allowed disabled:opacity-50',
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <input
        v-model="model"
        type="checkbox"
        v-bind="boundAttrs"
        :class="checkboxClass"
    />
</template>
