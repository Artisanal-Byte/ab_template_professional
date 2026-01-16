<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import Button from '@/components/ui/Button.vue';
import { splitAttrs } from '@/lib/attrs';
import { cn } from '@/lib/utils';

// Slots:
// - value: Selected value content.
// - meta: Optional trailing content (e.g., count badge).
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: '',
    },
});

const attrs = useAttrs() as Record<string, unknown>;

const attrSplit = computed(() => splitAttrs(attrs));
const triggerClass = computed(() =>
    cn(
        'w-full justify-between text-left border-border',
        props.class,
        attrSplit.value.className,
    ),
);
</script>

<template>
    <Button
        variant="outline"
        size="md"
        :disabled="props.disabled"
        :class="triggerClass"
        v-bind="attrSplit.rest"
    >
        <span class="flex flex-wrap items-center gap-2">
            <slot name="value" />
        </span>
        <span class="flex items-center gap-2">
            <slot name="meta" />
            <IconLucideChevronDown class="h-4 w-4 text-foreground-faint" />
        </span>
    </Button>
</template>
