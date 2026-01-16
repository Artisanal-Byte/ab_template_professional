<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/components/ui/Button.vue';
import { cn } from '@/lib/utils';

// Slots:
// - icon: Optional leading icon in the trigger.
// - trigger-content: Optional content inside the trigger button.
// - default: Inline dropdown content.
const props = defineProps({
    triggerLabel: {
        type: String,
        default: '',
    },
    triggerVariant: {
        type: String,
        default: 'secondary',
    },
    triggerSize: {
        type: String,
        default: 'md',
    },
    triggerClass: {
        type: String,
        default: '',
    },
    triggerDisabled: {
        type: Boolean,
        default: false,
    },
    showChevron: {
        type: Boolean,
        default: true,
    },
    contentClass: {
        type: String,
        default: '',
    },
    width: {
        type: String,
        default: 'md',
    },
});

const open = defineModel('open', {
    type: Boolean,
    default: false,
});

const widthClasses: Record<string, string> = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full min-w-0',
};

const wrapperClasses = computed(() =>
    cn('grid gap-2', widthClasses[props.width] || ''),
);

const triggerClasses = computed(() =>
    cn('w-full justify-between', props.triggerClass),
);

const contentClasses = computed(() =>
    cn(
        'rounded-md border border-border-subtle bg-card p-2 text-sm text-card-foreground shadow-sm',
        props.contentClass,
    ),
);

const toggleOpen = () => {
    if (props.triggerDisabled) {
        return;
    }
    open.value = !open.value;
};
</script>

<template>
    <div :class="wrapperClasses">
        <Button
            :class="triggerClasses"
            :variant="props.triggerVariant"
            :size="props.triggerSize"
            :disabled="props.triggerDisabled"
            :aria-expanded="open"
            @click="toggleOpen"
        >
            <span class="flex min-w-0 items-center gap-2">
                <span v-if="$slots.icon" class="shrink-0">
                    <slot name="icon" />
                </span>
                <span
                    v-if="props.triggerLabel"
                    class="truncate"
                >
                    {{ props.triggerLabel }}
                </span>
                <slot name="trigger-content" />
            </span>
            <span
                v-if="props.showChevron"
                class="ml-auto flex items-center text-foreground-faint"
            >
                <IconLucideChevronUp v-if="open" class="h-4 w-4" />
                <IconLucideChevronDown v-else class="h-4 w-4" />
            </span>
        </Button>

        <div
            :class="[
                'grid transition-[grid-template-rows,opacity] duration-200 ease-out',
                open ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0 pointer-events-none',
            ]"
            :aria-hidden="!open"
        >
            <div class="overflow-hidden">
                <div :class="contentClasses">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
