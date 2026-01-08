<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

// Slots:
// - image: Custom image content (defaults to <img>).
// - fallback: Custom fallback content (defaults to initials).
const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
    src: {
        type: String,
        default: '',
    },
    alt: {
        type: String,
        default: '',
    },
    fallback: {
        type: String,
        default: '',
    },
});

const slots = useSlots();
const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const sizeClasses: Record<string, string> = {
    sm: 'h-8 w-8',
    md: 'h-10 w-10',
    lg: 'h-12 w-12',
};

const avatarClass = computed(() =>
    cn(
        'relative inline-flex shrink-0 overflow-hidden rounded-full bg-secondary-subtle',
        sizeClasses[props.size] || sizeClasses.md,
        attrsSplit.value.className,
    ),
);

const shouldShowImage = computed(() => Boolean(props.src) || Boolean(slots.image));
const shouldShowFallback = computed(
    () => Boolean(slots.fallback) || Boolean(props.fallback),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <div v-bind="boundAttrs" :class="avatarClass">
        <template v-if="shouldShowImage">
            <slot name="image">
                <img
                    :src="props.src"
                    :alt="props.alt"
                    class="h-full w-full object-cover"
                />
            </slot>
        </template>
        <template v-else-if="shouldShowFallback">
            <slot name="fallback">
                <div
                    class="flex h-full w-full items-center justify-center bg-secondary-active text-sm font-medium text-foreground"
                >
                    {{ props.fallback }}
                </div>
            </slot>
        </template>
    </div>
</template>

