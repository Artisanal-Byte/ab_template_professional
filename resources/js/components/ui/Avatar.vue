<script setup lang="ts">
import { computed, ref, useAttrs, useSlots, watch } from 'vue';
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

const imageFailed = ref(false);

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

const shouldShowImage = computed(
    () => Boolean(slots.image) || (Boolean(props.src) && !imageFailed.value),
);
const shouldShowFallback = computed(() => {
    if (Boolean(slots.image)) {
        return false;
    }
    return (
        (Boolean(slots.fallback) || Boolean(props.fallback)) &&
        (!props.src || imageFailed.value)
    );
});

const boundAttrs = computed(() => attrsSplit.value.rest);

watch(
    () => props.src,
    () => {
        imageFailed.value = false;
    },
);

const handleImageError = () => {
    imageFailed.value = true;
};
</script>

<template>
    <div v-bind="boundAttrs" :class="avatarClass">
        <template v-if="shouldShowImage">
            <slot name="image">
                <img
                    :src="props.src"
                    :alt="props.alt"
                    class="h-full w-full object-cover"
                    @error="handleImageError"
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
