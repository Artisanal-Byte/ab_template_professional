<script setup lang="ts">
import { computed, ref } from 'vue';
import ListboxDropdown from '@/components/ui/ListboxDropdown.vue';
import SelectTrigger from '@/components/ui/SelectTrigger.vue';
import { getOptionLabel, getOptionValue } from '@/composables/useSelectDisplay';
import type { SelectOption } from '@/types';
import FieldError from '@/components/ui/FieldError.vue';

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
    align: {
        type: String,
        default: 'start',
    },
    side: {
        type: String,
        default: 'bottom',
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
    <div class="grid gap-1">
        <ListboxDropdown
            v-model:open="open"
            :options="props.options"
            :selected-values="[model]"
            :width="props.width"
            :align="props.align"
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
        <FieldError :error="props.error" />
    </div>
</template>
