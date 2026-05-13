<script setup lang="ts">
import { computed } from 'vue';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
    DialogTitle,
} from 'reka-ui';
import { cn } from '@/lib/utils';

// Slots:
// - default: Drawer content.
// - trigger: Trigger element (for non-modal usage).
// - close: Close button slot.
// - title: Drawer title (rendered visibly if provided, otherwise sr-only).
// - description: Drawer description (rendered visibly if provided, otherwise sr-only).
const props = defineProps({
    modal: {
        type: Boolean,
        default: true,
    },
    direction: {
        type: String,
        default: 'left',
    },
    size: {
        type: String,
        default: 'md',
    },
    contentClass: {
        type: String,
        default: '',
    },
    showClose: {
        type: Boolean,
        default: true,
    },
});

const open = defineModel('open', {
    type: Boolean,
    default: undefined,
});

const sizeClasses: Record<string, string> = {
    sm: 'w-72',
    md: 'w-96',
    lg: 'w-[32rem]',
    full: 'w-full',
};

const contentClasses = computed(() =>
    cn(
        'fixed z-50 border border-border bg-card text-card-foreground shadow-lg will-change-transform',
        props.direction === 'left'
            ? 'inset-y-0 left-0 border-r'
            : 'inset-y-0 right-0 border-l',
        sizeClasses[props.size] || sizeClasses.md,
        props.contentClass,
    ),
);

const contentTransition = computed(() => {
    const base = {
        enterActive: 'transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]',
        leaveActive: 'transition-transform duration-250 ease-[cubic-bezier(0.4,0,0.2,1)]',
    };

    if (props.direction === 'left') {
        return {
            ...base,
            enterFrom: '-translate-x-full',
            enterTo: 'translate-x-0',
            leaveFrom: 'translate-x-0',
            leaveTo: '-translate-x-full',
        };
    }

    return {
        ...base,
        enterFrom: 'translate-x-full',
        enterTo: 'translate-x-0',
        leaveFrom: 'translate-x-0',
        leaveTo: 'translate-x-full',
    };
});

const overlayTransition = {
    enterActive: 'transition-opacity duration-200 ease-out',
    enterFrom: 'opacity-0',
    enterTo: 'opacity-100',
    leaveActive: 'transition-opacity duration-150 ease-in',
    leaveFrom: 'opacity-100',
    leaveTo: 'opacity-0',
};
</script>

<template>
    <DialogRoot v-model:open="open" :modal="props.modal">
        <slot name="trigger" />
        <DialogPortal>
            <Transition
                :enter-active-class="overlayTransition.enterActive"
                :enter-from-class="overlayTransition.enterFrom"
                :enter-to-class="overlayTransition.enterTo"
                :leave-active-class="overlayTransition.leaveActive"
                :leave-from-class="overlayTransition.leaveFrom"
                :leave-to-class="overlayTransition.leaveTo"
            >
                <DialogOverlay
                    v-if="open"
                    class="fixed inset-0 z-40 bg-overlay"
                />
            </Transition>

            <Transition
                :enter-active-class="contentTransition.enterActive"
                :enter-from-class="contentTransition.enterFrom"
                :enter-to-class="contentTransition.enterTo"
                :leave-active-class="contentTransition.leaveActive"
                :leave-from-class="contentTransition.leaveFrom"
                :leave-to-class="contentTransition.leaveTo"
            >
                <DialogContent v-if="open" :class="contentClasses">
                    <DialogTitle :class="$slots.title ? 'text-lg font-semibold' : 'sr-only'">
                        <slot name="title">Drawer</slot>
                    </DialogTitle>
                    <DialogDescription :class="$slots.description ? 'text-sm text-foreground-subtle' : 'sr-only'">
                        <slot name="description">Drawer panel</slot>
                    </DialogDescription>

                    <div class="flex h-full flex-col">
                        <slot />
                    </div>

                    <DialogClose v-if="showClose" as-child>
                        <slot name="close">
                            <button
                                type="button"
                                class="absolute right-4 top-4 rounded-md px-2 py-1 text-xs text-foreground-faint transition hover:text-foreground"
                            >
                                Close
                            </button>
                        </slot>
                    </DialogClose>
                </DialogContent>
            </Transition>
        </DialogPortal>
    </DialogRoot>
</template>
