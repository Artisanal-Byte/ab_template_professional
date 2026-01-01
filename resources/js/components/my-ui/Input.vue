<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
});

const model = defineModel({
    type: [String, Number],
    default: '',
});

const attrs = useAttrs();

const baseClasses =
    'h-10 w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground placeholder:text-neutral/70 transition-colors outline-none focus-visible:ring-2 focus-visible:ring-primary/30 disabled:cursor-not-allowed disabled:opacity-50';

const sizeClasses = {
    sm: 'h-8 px-2 text-sm',
    md: 'h-10 px-3 text-sm',
    lg: 'h-11 px-4 text-base',
};

const inputClass = computed(() =>
    cn(baseClasses, sizeClasses[props.size] || sizeClasses.md, attrs.class),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <input
        v-model="model"
        v-bind="boundAttrs"
        :class="inputClass"
    />
</template>
