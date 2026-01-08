<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

const props = defineProps({
    orientation: {
        type: String,
        default: 'horizontal',
    },
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const separatorClass = computed(() =>
    cn(
        props.orientation === 'vertical'
            ? 'h-full w-px'
            : 'h-px w-full',
        'bg-border',
        attrsSplit.value.className,
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <div v-bind="boundAttrs" :class="separatorClass" role="separator" />
</template>
