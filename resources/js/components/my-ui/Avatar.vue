<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { cn } from '@/lib/utils';

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
const attrs = useAttrs();

const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-10 w-10',
    lg: 'h-12 w-12',
};

const avatarClass = computed(() =>
    cn(
        'relative inline-flex shrink-0 overflow-hidden rounded-full bg-secondary/40',
        sizeClasses[props.size] || sizeClasses.md,
        attrs.class,
    ),
);

const shouldShowImage = computed(() => Boolean(props.src) || Boolean(slots.image));
const shouldShowFallback = computed(
    () => Boolean(slots.fallback) || Boolean(props.fallback),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
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
                    class="flex h-full w-full items-center justify-center bg-secondary/60 text-sm font-medium text-foreground"
                >
                    {{ props.fallback }}
                </div>
            </slot>
        </template>
    </div>
</template>
