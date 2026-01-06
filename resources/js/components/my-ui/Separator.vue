<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    orientation: {
        type: String,
        default: 'horizontal',
    },
});

const attrs = useAttrs();

const separatorClass = computed(() =>
    cn(
        props.orientation === 'vertical'
            ? 'h-full w-px'
            : 'h-px w-full',
        'bg-border',
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <div v-bind="boundAttrs" :class="separatorClass" role="separator" />
</template>
