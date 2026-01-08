<script setup lang="ts">
import { computed } from 'vue';
import {
    PopoverContent,
    PopoverPortal,
    PopoverRoot,
    PopoverTrigger,
} from 'reka-ui';
import { cn } from '@/lib/utils';

// Slots:
// - trigger: Trigger element.
// - default: Popover content.
const props = defineProps({
    side: {
        type: String,
        default: 'bottom',
    },
    align: {
        type: String,
        default: 'start',
    },
    sideOffset: {
        type: Number,
        default: 6,
    },
    width: {
        type: String,
        default: 'md',
    },
    contentClass: {
        type: String,
        default: '',
    },
    matchTriggerWidth: {
        type: Boolean,
        default: false,
    },
    triggerWidthVar: {
        type: String,
        default: '--reka-popover-trigger-width',
    },
    rootComponent: {
        type: [String, Object],
        default: PopoverRoot,
    },
    triggerComponent: {
        type: [String, Object],
        default: PopoverTrigger,
    },
    contentComponent: {
        type: [String, Object],
        default: PopoverContent,
    },
    portalComponent: {
        type: [String, Object],
        default: PopoverPortal,
    },
});

const open = defineModel('open', {
    type: Boolean,
    default: undefined,
});

const widthClasses: Record<string, string> = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full min-w-0',
};

const widthStyles: Record<string, string> = {
    xs: '8rem',
    sm: '10rem',
    md: '14rem',
    lg: '18rem',
    xl: '20rem',
    full: '100%',
};

const sideValue = computed(
    () => props.side as 'top' | 'bottom' | 'left' | 'right',
);
const alignValue = computed(
    () => props.align as 'start' | 'center' | 'end',
);

const contentClasses = computed(() =>
    cn(
        'z-50 rounded-lg border border-border bg-card p-2 text-sm text-card-foreground shadow-lg',
        widthClasses[props.width] || widthClasses.md,
        'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
        props.contentClass,
    ),
);

const contentStyle = computed(() => {
    if (props.matchTriggerWidth || props.width === 'full') {
        return {
            width: `var(${props.triggerWidthVar})`,
            minWidth: `var(${props.triggerWidthVar})`,
        };
    }

    const width = widthStyles[props.width] || widthStyles.md;
    return {
        width,
        minWidth: width,
    };
});
</script>

<template>
    <component :is="props.rootComponent" v-model:open="open">
        <component :is="props.triggerComponent" as-child>
            <slot name="trigger" />
        </component>
        <component :is="props.portalComponent">
            <component
                :is="props.contentComponent"
                :side="sideValue"
                :align="alignValue"
                :side-offset="props.sideOffset"
                :class="contentClasses"
                :style="contentStyle"
            >
                <slot />
            </component>
        </component>
    </component>
</template>
