<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

// Slots:
// - title: Optional title content.
// - description: Optional description content.
// - default: Fallback description content.
const props = defineProps({
    variant: {
        type: String,
        default: 'neutral',
    },
    as: {
        type: [String, Object],
        default: 'div',
    },
});

const slots = useSlots();
const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const variantClasses: Record<string, string> = {
    neutral: 'border-border-subtle bg-card text-card-foreground',
    info: 'border-info-border bg-info-soft text-foreground',
    success: 'border-success-border bg-success-soft text-foreground',
    warning: 'border-warning-border bg-warning-soft text-foreground',
    error: 'border-error-border bg-error-soft text-foreground',
};

const alertClass = computed(() =>
    cn(
        'rounded-lg border px-4 py-3 text-sm',
        variantClasses[props.variant] || variantClasses.neutral,
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);

const hasTitle = computed(() => Boolean(slots.title));
const hasDescription = computed(
    () => Boolean(slots.description) || Boolean(slots.default),
);
</script>

<template>
    <component :is="props.as" v-bind="boundAttrs" :class="alertClass">
        <div v-if="hasTitle" class="mb-1 text-sm font-semibold leading-none">
            <slot name="title" />
        </div>
        <div v-if="hasDescription" class="text-sm text-foreground-subtle">
            <slot name="description">
                <slot />
            </slot>
        </div>
    </component>
</template>

