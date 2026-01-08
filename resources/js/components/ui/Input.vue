<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { splitAttrs } from '@/lib/attrs';
import { inputBase } from '@/lib/uiClass';
import FieldError from '@/components/ui/FieldError.vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    size: {
        type: String,
        default: 'md',
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
</script>

<template>
    <div class="grid gap-1">
        <input
            v-model="model"
            v-bind="boundAttrs"
            :class="inputClass"
        />
        <FieldError :error="props.error" />
    </div>
</template>
