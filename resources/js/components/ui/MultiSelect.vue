<script setup lang="ts">
import { computed, ref } from 'vue';
import Badge from '@/components/ui/Badge.vue';
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
        default: 'Select options',
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
    type: Array,
    default: () => [],
});

const open = ref(false);

const options = computed(() => props.options as SelectOption[]);

const selectedOptions = computed(() =>
    options.value.filter((option) =>
        model.value.includes(getOptionValue(option)),
    ),
);

const handleSelect = (option: SelectOption) => {
    const value = getOptionValue(option);
    if (model.value.includes(value)) {
        model.value = model.value.filter((item: any) => item !== value);
        return;
    }
    model.value = [...model.value, value];
};
</script>

<template>
    <div class="grid gap-1">
        <ListboxDropdown
            v-model:open="open"
            :options="props.options"
            :selected-values="model"
            :width="props.width"
            :align="props.align"
            :side="props.side"
            :side-offset="props.sideOffset"
            :loading="props.loading"
            :no-results-text="props.noResultsText"
            :close-on-select="false"
            @select="handleSelect"
        >
            <template #trigger>
                <SelectTrigger :disabled="props.disabled">
                    <template #value>
                        <span v-if="selectedOptions.length === 0" class="text-foreground-faint">
                            {{ props.placeholder }}
                        </span>
                        <Badge
                            v-for="option in selectedOptions"
                            :key="String(getOptionValue(option))"
                        >
                            {{ getOptionLabel(option) }}
                        </Badge>
                    </template>
                    <template #meta>
                        <span
                            v-if="selectedOptions.length"
                            class="rounded-full bg-secondary px-2 py-0.5 text-xs font-semibold text-foreground"
                        >
                            {{ selectedOptions.length }}
                        </span>
                    </template>
                </SelectTrigger>
            </template>
        </ListboxDropdown>
        <template v-if="props.name && model.length">
            <input
                v-for="value in model"
                :key="String(value)"
                type="hidden"
                :name="`${props.name}[]`"
                :value="value"
            />
        </template>
        <FieldError :error="props.error" />
    </div>
</template>

