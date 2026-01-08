<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/components/ui/Button.vue';
import PopoverBase from '@/components/ui/PopoverBase.vue';
import {
    DropdownMenuContent,
    DropdownMenuPortal,
    DropdownMenuRoot,
    DropdownMenuTrigger,
} from 'reka-ui';
import { cn } from '@/lib/utils';

// Slots:
// - trigger-content: Content inside the trigger button.
// - default: Dropdown menu content.
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
    contentClass: {
        type: String,
        default: '',
    },
    width: {
        type: String,
        default: 'md',
    },
    menuWidth: {
        type: String,
        default: '',
    },
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
    triggerTestId: {
        type: String,
        default: '',
    },
});

const open = defineModel('open', {
    type: Boolean,
    default: undefined,
});

const triggerWidthClasses: Record<string, string> = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full',
};

const triggerClasses = computed(() =>
    cn(
        triggerWidthClasses[props.width] || '',
        props.triggerClass,
    ),
);


const menuWidthValue = computed(() => props.menuWidth || props.width);

const contentClasses = computed(() => props.contentClass);
</script>

<template>
    <PopoverBase
        v-model:open="open"
        :root-component="DropdownMenuRoot"
        :trigger-component="DropdownMenuTrigger"
        :content-component="DropdownMenuContent"
        :portal-component="DropdownMenuPortal"
        :side="props.side"
        :align="props.align"
        :side-offset="props.sideOffset"
        :width="menuWidthValue"
        :content-class="contentClasses"
        :match-trigger-width="menuWidthValue === 'full'"
        trigger-width-var="--reka-dropdown-menu-trigger-width"
    >
        <template #trigger>
            <Button
                :class="triggerClasses"
                :variant="props.triggerVariant"
                :size="props.triggerSize"
                :disabled="props.triggerDisabled"
                :data-test="props.triggerTestId || undefined"
            >
                <span v-if="props.triggerLabel">{{ props.triggerLabel }}</span>
                <slot name="trigger-content" />
            </Button>
        </template>
        <slot />
    </PopoverBase>
</template>
