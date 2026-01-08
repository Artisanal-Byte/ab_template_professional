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

const normalizeValue = (value: string) => {
    let next = value.replace(/[^0-9.]/g, '');
    const firstDot = next.indexOf('.');
    if (firstDot !== -1) {
        next =
            next.slice(0, firstDot + 1) +
            next.slice(firstDot + 1).replace(/\./g, '');
    }
    return next;
};

const allowKey = (event: KeyboardEvent) => {
    if (event.ctrlKey || event.metaKey || event.altKey) {
        return true;
    }
    const allowed = [
        'Backspace',
        'Delete',
        'Tab',
        'ArrowLeft',
        'ArrowRight',
        'ArrowUp',
        'ArrowDown',
        'Home',
        'End',
        'Enter',
    ];
    if (allowed.includes(event.key)) {
        return true;
    }
    if (event.key === '.') {
        const current = String(model.value ?? '');
        if (!current.includes('.')) {
            return true;
        }
        const input = event.currentTarget as HTMLInputElement | null;
        if (input?.selectionStart != null && input.selectionEnd != null) {
            const selection = current.slice(
                input.selectionStart,
                input.selectionEnd,
            );
            return selection.includes('.');
        }
        return false;
    }
    return /^[0-9]$/.test(event.key);
};

const onKeydown = (event: KeyboardEvent) => {
    if (!allowKey(event)) {
        event.preventDefault();
    }
};

const onInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const next = normalizeValue(target.value);
    if (next !== target.value) {
        target.value = next;
    }
    model.value = next;
};
</script>

<template>
    <div class="grid gap-1">
        <input
            v-bind="boundAttrs"
            :value="model"
            type="text"
            inputmode="decimal"
            pattern="[0-9]*[.,]?[0-9]*"
            :class="inputClass"
            @keydown="onKeydown"
            @input="onInput"
        />
        <FieldError :error="props.error" />
    </div>
</template>
