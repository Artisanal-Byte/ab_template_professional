<script setup lang="ts">
import { computed, useAttrs, watch } from 'vue';
import { splitAttrs } from '@/lib/attrs';
import { inputBase } from '@/lib/uiClass';
import FormError from '@/components/ui/FormError.vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
    defaultValue: {
        type: [String, Number],
        default: undefined,
    },
    error: {
        type: [String, Array, Object],
        default: '',
    },
});

const model = defineModel({
    default: '',
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const inputClass = computed(() =>
    inputBase(props.size, attrsSplit.value.className),
);

const boundAttrs = computed(() => attrsSplit.value.rest);

watch(
    () => props.defaultValue,
    (value) => {
        if ((model.value === '' || model.value === null || model.value === undefined) && value !== undefined && value !== null) {
            model.value = value;
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="grid gap-1">
        <input
            v-model="model"
            v-bind="boundAttrs"
            :class="inputClass"
        />
        <FormError :error="props.error" />
    </div>
</template>

