<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    as: {
        type: [String, Object],
        default: 'button',
    },
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const attrs = useAttrs();

const baseClasses =
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors outline-none focus-visible:ring-2 focus-visible:ring-primary/30 disabled:pointer-events-none disabled:opacity-50';

const variantClasses = {
    primary: 'bg-primary text-primary-foreground hover:bg-primary/90',
    secondary: 'bg-secondary text-foreground hover:bg-secondary/80',
    outline:
        'border border-border bg-background text-foreground hover:bg-secondary/40',
    ghost: 'bg-transparent text-foreground hover:bg-secondary/40',
    success: 'bg-success text-white hover:bg-success/90',
    info: 'bg-info text-white hover:bg-info/90',
    warning: 'bg-warning text-white hover:bg-warning/90',
    destructive: 'bg-error text-white hover:bg-error/90',
    link: 'text-primary underline underline-offset-4 hover:opacity-90',
};

const sizeClasses = {
    sm: 'h-8 px-3',
    md: 'h-9 px-4',
    lg: 'h-10 px-6 text-base',
    xl: 'h-12 px-7 text-base',
    icon: 'h-9 w-9',
};

const buttonClass = computed(() =>
    cn(
        baseClasses,
        variantClasses[props.variant] || variantClasses.primary,
        sizeClasses[props.size] || sizeClasses.md,
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <component
        :is="as"
        v-bind="boundAttrs"
        :type="as === 'button' ? type : undefined"
        :disabled="disabled"
        :class="buttonClass"
    >
        <slot />
    </component>
</template>
