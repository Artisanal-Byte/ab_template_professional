<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { splitAttrs } from '@/lib/attrs';
import { labelBase } from '@/lib/uiClass';

const props = defineProps({
    for: {
        type: String,
        default: undefined,
    },
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const labelClass = computed(() => labelBase(attrsSplit.value.className));

const boundAttrs = computed(() => attrsSplit.value.rest);
</script>

<template>
    <label :for="props.for" v-bind="boundAttrs" :class="labelClass">
        <slot />
    </label>
</template>
