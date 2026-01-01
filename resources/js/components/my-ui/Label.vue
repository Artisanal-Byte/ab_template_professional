<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    for: {
        type: String,
        default: undefined,
    },
});

const attrs = useAttrs();

const labelClass = computed(() =>
    cn(
        'text-sm font-medium text-foreground leading-none',
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});
</script>

<template>
    <label :for="props.for" v-bind="boundAttrs" :class="labelClass">
        <slot />
    </label>
</template>
