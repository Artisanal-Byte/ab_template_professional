<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/components/my-ui/Button.vue';
import {
    DropdownMenuContent,
    DropdownMenuPortal,
    DropdownMenuRoot,
    DropdownMenuTrigger,
} from 'reka-ui';
import { cn } from '@/lib/utils';

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

const widthClasses = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full min-w-0',
};

const triggerWidthClasses = {
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


const widthStyles = {
    xs: '8rem',
    sm: '10rem',
    md: '14rem',
    lg: '18rem',
    xl: '20rem',
    full: '100%',
};

const menuWidthValue = computed(() => props.menuWidth || props.width);

const contentClasses = computed(() =>
    cn(
        'z-50 rounded-lg border border-border bg-card p-2 text-sm text-card-foreground shadow-lg',
        widthClasses[menuWidthValue.value] || widthClasses.md,
        'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
        props.contentClass,
    ),
);

const contentStyle = computed(() => {
    if (menuWidthValue.value === 'full') {
        return {
            width: 'var(--reka-dropdown-menu-trigger-width)',
            minWidth: 'var(--reka-dropdown-menu-trigger-width)',
        };
    }

    const width = widthStyles[menuWidthValue.value] || widthStyles.md;
    return {
        width,
        minWidth: width,
    };
});
</script>

<template>
    <DropdownMenuRoot v-model:open="open">
        <DropdownMenuTrigger as-child>
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
        </DropdownMenuTrigger>
        <DropdownMenuPortal>
            <DropdownMenuContent
                :side="props.side"
                :align="props.align"
                :side-offset="props.sideOffset"
                :class="contentClasses"
                :style="contentStyle"
            >
                <slot />
            </DropdownMenuContent>
        </DropdownMenuPortal>
    </DropdownMenuRoot>
</template>
