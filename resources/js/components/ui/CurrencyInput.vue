<script setup lang="ts">
import { computed, useAttrs, watch } from 'vue';
import FormError from '@/components/ui/FormError.vue';
import {
    formatCurrencyNumber,
    getCurrencySymbol,
    resolveCurrencyLocale,
    sanitizeCurrencyValue,
} from '@/composables/useCurrencyLocale';
import { splitAttrs } from '@/lib/attrs';
import { cn } from '@/lib/utils';
import { inputBase, inputSizeClasses } from '@/lib/uiClass';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    size: {
        type: String,
        default: 'md',
    },
    locale: {
        type: String,
        default: 'en-IN',
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

const resolvedLocale = computed(() => resolveCurrencyLocale(props.locale));

const inputClass = computed(() =>
    cn(
        inputBase(props.size, attrsSplit.value.className),
        'pl-9 text-right',
    ),
);

const symbolClass = computed(() =>
    cn(
        'pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-foreground-subtle',
        inputSizeClasses[props.size] || inputSizeClasses.md,
        'h-auto px-0 py-0',
    ),
);

const boundAttrs = computed(() => attrsSplit.value.rest);
const symbolText = computed(() =>
    getCurrencySymbol(
        resolvedLocale.value.locale,
        resolvedLocale.value.currency,
    ),
);

const displayValue = computed(() => {
    const currentValue = String(model.value ?? '');
    return formatCurrencyNumber(currentValue, resolvedLocale.value.locale);
});

const editableCharPattern = /[0-9.]/;

const countEditableChars = (value: string, caret: number) =>
    value
        .slice(0, caret)
        .split('')
        .filter((char) => editableCharPattern.test(char)).length;

const caretFromEditableCharCount = (value: string, count: number) => {
    if (count <= 0) {
        return 0;
    }

    let seen = 0;
    for (let index = 0; index < value.length; index += 1) {
        if (!editableCharPattern.test(value[index])) {
            continue;
        }
        seen += 1;
        if (seen >= count) {
            return index + 1;
        }
    }

    return value.length;
};

const onInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const rawValue = input.value;
    const caret = input.selectionStart ?? rawValue.length;
    const editableCharsBeforeCaret = countEditableChars(rawValue, caret);
    const sanitized = sanitizeCurrencyValue(rawValue);

    model.value = sanitized;

    const formatted = formatCurrencyNumber(
        sanitized,
        resolvedLocale.value.locale,
    );

    if (formatted !== rawValue) {
        input.value = formatted;
    }

    const nextCaret = caretFromEditableCharCount(
        formatted,
        editableCharsBeforeCaret,
    );
    requestAnimationFrame(() => {
        input.setSelectionRange(nextCaret, nextCaret);
    });
};

const onKeydown = (event: KeyboardEvent) => {
    if (event.ctrlKey || event.metaKey || event.altKey) {
        return;
    }

    const allowedKeys = [
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

    if (allowedKeys.includes(event.key)) {
        return;
    }

    if (event.key === '.') {
        const current = String(model.value ?? '');
        if (current.includes('.')) {
            event.preventDefault();
        }
        return;
    }

    if (!/^[0-9]$/.test(event.key)) {
        event.preventDefault();
    }
};

watch(
    () => props.defaultValue,
    (value) => {
        if (
            (model.value === '' ||
                model.value === null ||
                model.value === undefined) &&
            value !== undefined &&
            value !== null
        ) {
            model.value = sanitizeCurrencyValue(String(value));
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="grid gap-1">
        <div class="relative">
            <span :class="symbolClass">{{ symbolText }}</span>
            <input
                v-bind="boundAttrs"
                type="text"
                inputmode="decimal"
                :value="displayValue"
                :class="inputClass"
                @keydown="onKeydown"
                @input="onInput"
            />
        </div>
        <FormError :error="props.error" />
    </div>
</template>
