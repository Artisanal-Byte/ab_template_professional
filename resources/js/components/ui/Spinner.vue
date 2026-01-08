<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const sizeClasses: Record<string, string> = {
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6',
};

const spinnerClass = computed(() =>
    cn(
        'animate-spin text-foreground-subtle',
        sizeClasses[props.size] || sizeClasses.md,
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <svg
        viewBox="0 0 24 24"
        fill="none"
        v-bind="boundAttrs"
        :class="spinnerClass"
        role="status"
        aria-live="polite"
    >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        />
        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        />
    </svg>
</template>

