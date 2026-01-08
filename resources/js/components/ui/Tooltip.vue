<script setup lang="ts">
import { computed } from 'vue';
import {
    TooltipArrow,
    TooltipContent,
    TooltipPortal,
    TooltipProvider,
    TooltipRoot,
    TooltipTrigger,
} from 'reka-ui';
import { cn } from '@/lib/utils';

// Slots:
// - trigger: Trigger element.
// - default: Tooltip content.
const props = defineProps({
    delayDuration: {
        type: Number,
        default: 0,
    },
    side: {
        type: String,
        default: 'top',
    },
    align: {
        type: String,
        default: 'center',
    },
    sideOffset: {
        type: Number,
        default: 6,
    },
    contentClass: {
        type: String,
        default: '',
    },
    showArrow: {
        type: Boolean,
        default: true,
    },
});

const sideValue = computed(
    () => props.side as 'top' | 'bottom' | 'left' | 'right',
);
const alignValue = computed(
    () => props.align as 'start' | 'center' | 'end',
);

const contentClasses = computed(() =>
    cn(
        'z-50 w-fit rounded-md bg-foreground px-3 py-1.5 text-xs text-background shadow-sm',
        'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
        'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        'data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2',
        'data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2',
        props.contentClass,
    ),
);
</script>

<template>
    <TooltipProvider>
        <TooltipRoot :delay-duration="props.delayDuration">
            <TooltipTrigger as-child>
                <slot name="trigger" />
            </TooltipTrigger>
            <TooltipPortal>
                <TooltipContent
                    :side="sideValue"
                    :align="alignValue"
                    :side-offset="props.sideOffset"
                    :class="contentClasses"
                >
                    <slot />
                    <TooltipArrow
                        v-if="props.showArrow"
                        class="size-2.5 translate-y-[calc(-50%_-_2px)] rotate-45 rounded-[2px] bg-foreground fill-foreground"
                    />
                </TooltipContent>
            </TooltipPortal>
        </TooltipRoot>
    </TooltipProvider>
</template>
