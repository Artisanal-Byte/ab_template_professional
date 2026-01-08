<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

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

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const variantClasses: Record<string, string> = {
    neutral: 'border-border-subtle bg-secondary-subtle text-foreground',
    primary: 'border-primary-border bg-primary-soft text-primary',
    success: 'border-success-border bg-success-soft text-foreground',
    warning: 'border-warning-border bg-warning-soft text-foreground',
    error: 'border-error-border bg-error-soft text-foreground',
};

const badgeClass = computed(() =>
    cn(
        'inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-medium',
        variantClasses[props.variant] || variantClasses.neutral,
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <component :is="props.as" v-bind="boundAttrs" :class="badgeClass">
        <slot />
    </component>
</template>


