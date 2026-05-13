<script setup lang="ts">
import { computed, useAttrs, type Component } from 'vue';
import { splitAttrs } from '@/lib/attrs';
import { buttonBase } from '@/lib/uiClass';

interface Props {
    as?: string | Component;
    type?: string;
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'success' | 'info' | 'warning' | 'destructive' | 'link';
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'icon';
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    as: 'button',
    type: 'button',
    variant: 'primary',
    size: 'md',
    disabled: false,
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const buttonClass = computed(() =>
    buttonBase(props.variant, props.size, attrsSplit.value.className),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <component
        :is="as"
        v-bind="boundAttrs"
        :type="as === 'button' ? type : undefined"
        :disabled="disabled"
        :class="buttonClass"
    >
        <slot />
    </component>
</template>
