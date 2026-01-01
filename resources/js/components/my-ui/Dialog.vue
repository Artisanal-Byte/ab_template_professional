<script setup lang="ts">
import { computed, useSlots } from 'vue';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
    DialogTitle,
    DialogTrigger,
} from 'reka-ui';
import { cn } from '@/lib/utils';

const props = defineProps({
    modal: {
        type: Boolean,
        default: true,
    },
    transition: {
        type: String,
        default: 'scale',
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

const slots = useSlots();

const contentClasses = computed(() => {
    const baseClasses =
        props.transition === 'slide-left'
            ? 'fixed left-0 top-0 z-50 h-full border border-border bg-card text-card-foreground shadow-lg will-change-transform'
            : 'fixed left-1/2 top-1/2 z-50 w-[min(92vw,32rem)] -translate-x-1/2 -translate-y-1/2 rounded-lg border border-border bg-card p-6 text-card-foreground shadow-lg transition-all duration-200 ease-out';

    return cn(baseClasses, props.contentClass);
});

const closeDialog = () => {
    open.value = false;
};

const overlayTransition = {
    enterActive: 'transition-opacity duration-200 ease-out',
    enterFrom: 'opacity-0',
    enterTo: 'opacity-100',
    leaveActive: 'transition-opacity duration-150 ease-in',
    leaveFrom: 'opacity-100',
    leaveTo: 'opacity-0',
};

const contentTransition = computed(() => {
    if (props.transition === 'slide-left') {
        return {
            enterActive: 'transition-transform duration-320 ease-[cubic-bezier(0.22,1,0.36,1)]',
            enterFrom: '-translate-x-full',
            enterTo: 'translate-x-0',
            leaveActive: 'transition-transform duration-260 ease-[cubic-bezier(0.4,0,0.2,1)]',
            leaveFrom: 'translate-x-0',
            leaveTo: '-translate-x-full',
        };
    }

    return {
        enterActive: 'transition-all duration-200 ease-out',
        enterFrom: 'opacity-0 scale-95',
        enterTo: 'opacity-100 scale-100',
        leaveActive: 'transition-all duration-150 ease-in',
        leaveFrom: 'opacity-100 scale-100',
        leaveTo: 'opacity-0 scale-95',
    };
});

const defaultSlotClasses = computed(() =>
    props.transition === 'slide-left' ? 'flex h-full flex-col' : '',
);

</script>

<template>
    <DialogRoot v-model:open="open" :modal="props.modal">
        <DialogTrigger v-if="$slots.trigger" as-child>
            <slot name="trigger" />
        </DialogTrigger>
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
                    class="fixed inset-0 z-40 bg-black/40"
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
                    <div v-if="$slots.title || $slots.description" class="mb-4 space-y-1">
                        <DialogTitle v-if="$slots.title" class="text-lg font-semibold">
                            <slot name="title" />
                        </DialogTitle>
                        <DialogDescription v-if="$slots.description" class="text-sm text-foreground/70">
                            <slot name="description" />
                        </DialogDescription>
                    </div>

                    <div v-if="$slots.default" :class="defaultSlotClasses">
                        <slot />
                    </div>

                    <div v-if="$slots.footer" class="mt-6 flex items-center justify-end gap-2">
                        <slot name="footer" :close="closeDialog" />
                    </div>

                    <DialogClose v-if="props.showClose" as-child>
                        <button
                            type="button"
                            class="absolute right-4 top-4 rounded-md px-2 py-1 text-xs text-foreground/60 transition hover:text-foreground"
                        >
                            Close
                        </button>
                    </DialogClose>
                </DialogContent>
            </Transition>
        </DialogPortal>
    </DialogRoot>
</template>
