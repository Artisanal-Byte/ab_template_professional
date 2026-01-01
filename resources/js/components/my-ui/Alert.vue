<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { cn } from '@/lib/utils';

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
const attrs = useAttrs();

const variantClasses = {
    neutral: 'border-border/60 bg-card text-card-foreground',
    info: 'border-info/40 bg-info/10 text-foreground',
    success: 'border-success/40 bg-success/10 text-foreground',
    warning: 'border-warning/40 bg-warning/10 text-foreground',
    error: 'border-error/40 bg-error/10 text-foreground',
};

const alertClass = computed(() =>
    cn(
        'rounded-lg border px-4 py-3 text-sm',
        variantClasses[props.variant] || variantClasses.neutral,
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});

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
        <div v-if="hasDescription" class="text-sm text-foreground/75">
            <slot name="description">
                <slot />
            </slot>
        </div>
    </component>
</template>
