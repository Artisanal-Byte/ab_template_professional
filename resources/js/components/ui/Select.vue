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

const selectedOption = computed(() =>
    options.value.find(
        (option) =>
            getOptionValue(option) === model.value,
    ),
);

const handleSelect = (option: SelectOption) => {
    model.value = getOptionValue(option);
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
            @select="handleSelect"
        >
            <template #trigger>
                <SelectTrigger :disabled="props.disabled">
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

