<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    variant: {
        type: String,
        default: 'neutral',
    },
    as: {
        type: [String, Object],
        default: 'span',
    },
});

const attrs = useAttrs();

const variantClasses = {
    neutral: 'border-border/60 bg-secondary/40 text-foreground',
    primary: 'border-primary/30 bg-primary/10 text-primary',
    success: 'border-success/40 bg-success/10 text-foreground',
    warning: 'border-warning/40 bg-warning/10 text-foreground',
    error: 'border-error/40 bg-error/10 text-foreground',
};

const badgeClass = computed(() =>
    cn(
        'inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-medium',
        variantClasses[props.variant] || variantClasses.neutral,
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <component :is="props.as" v-bind="boundAttrs" :class="badgeClass">
        <slot />
    </component>
</template>
