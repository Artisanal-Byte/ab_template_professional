<script setup lang="ts">
import { computed, ref } from 'vue';
import ListboxDropdown from '@/components/ui/ListboxDropdown.vue';
import SelectTrigger from '@/components/ui/SelectTrigger.vue';
import { getOptionLabel, getOptionValue } from '@/composables/useSelectDisplay';
import type { SelectOption } from '@/types';
import FormError from '@/components/ui/FormError.vue';

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    name: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    width: {
        type: String,
        default: 'md',
    },
    side: {
        type: String,
        default: 'bottom',
        validator: (value: string) => ['top', 'bottom'].includes(value),
    },
    sideOffset: {
        type: Number,
        default: 6,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    noResultsText: {
        type: String,
        default: 'No results',
    },
    error: {
        type: [String, Array, Object],
        default: '',
    },
});

const model = defineModel({
    type: [String, Number, Object],
    default: '',
});

const open = ref(false);

const widthClasses: Record<string, string> = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full min-w-0',
};

const wrapperClass = computed(() => {
    const widthClass = widthClasses[props.width] || widthClasses.md;
    return `grid gap-1 ${widthClass}`;
});

const options = computed(() => props.options as SelectOption[]);
const enabledOptions = computed(() =>
    options.value.filter((option) => !option?.disabled),
);

const selectedOption = computed(() =>
    options.value.find(
        (option) =>
            getOptionValue(option) === model.value,
    ),
);

const handleSelect = (option: SelectOption) => {
    model.value = getOptionValue(option);
};

const handleSingleSelectArrowKey = (event: KeyboardEvent) => {
    if (props.disabled || open.value) {
        return;
    }

    if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
        return;
    }

    if (!enabledOptions.value.length) {
        return;
    }

    event.preventDefault();

    const currentIndex = enabledOptions.value.findIndex(
        (option) => getOptionValue(option) === model.value,
    );
    const fallbackIndex = event.key === 'ArrowDown' ? -1 : 0;
    const baseIndex = currentIndex === -1 ? fallbackIndex : currentIndex;
    const delta = event.key === 'ArrowDown' ? 1 : -1;
    const nextIndex = (baseIndex + delta + enabledOptions.value.length) % enabledOptions.value.length;

    model.value = getOptionValue(enabledOptions.value[nextIndex]);
};
</script>

<template>
    <div :class="wrapperClass">
        <ListboxDropdown
            v-model:open="open"
            :options="props.options"
            :selected-values="[model]"
            :width="props.width"
            :side="props.side"
            :side-offset="props.sideOffset"
            :loading="props.loading"
            :no-results-text="props.noResultsText"
            :open-on-arrow-keys="false"
            @select="handleSelect"
        >
            <template #trigger="{ onKeydown }">
                <SelectTrigger :disabled="props.disabled" @keydown="onKeydown" @keydown.capture="handleSingleSelectArrowKey">
                    <template #value>
                        <span class="truncate">
                            {{
                                selectedOption
                                    ? getOptionLabel(selectedOption)
                                    : props.placeholder
                            }}
                        </span>
                    </template>
                </SelectTrigger>
            </template>
        </ListboxDropdown>
        <input
            v-if="props.name"
            type="hidden"
            :name="props.name"
            :value="model"
        />
        <FormError :error="props.error" />
    </div>
</template>
