<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { splitAttrs } from '@/lib/attrs';
import { buttonBase } from '@/lib/uiClass';

const props = defineProps({
    as: {
        type: [String, Object],
        default: 'button',
    },
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const buttonClass = computed(() =>
    buttonBase(
        props.variant,
        props.size,
        attrsSplit.value.className,
    ),
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
