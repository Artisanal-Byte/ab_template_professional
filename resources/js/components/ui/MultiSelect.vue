<script setup lang="ts">
import { computed, ref } from 'vue';
import Badge from '@/components/ui/Badge.vue';
import Input from '@/components/ui/Input.vue';
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
        default: 'Select options',
    },
    searchPlaceholder: {
        type: String,
        default: 'Search...',
    },
    maxChips: {
        type: Number,
        default: 1,
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
    type: Array,
    default: () => [],
});

const open = ref(false);
const searchTerm = ref('');

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

const selectedOptions = computed(() =>
    options.value.filter((option) =>
        model.value.includes(getOptionValue(option)),
    ),
);

const visibleOptions = computed(() =>
    selectedOptions.value.slice(0, Math.max(1, props.maxChips)),
);

const hiddenCount = computed(() =>
    Math.max(0, selectedOptions.value.length - visibleOptions.value.length),
);

const handleSelect = (option: SelectOption) => {
    const value = getOptionValue(option);
    if (model.value.includes(value)) {
        model.value = model.value.filter((item: any) => item !== value);
        return;
    }
    model.value = [...model.value, value];
};

const handleOpenChange = (nextOpen: boolean) => {
    open.value = nextOpen;

    if (!nextOpen) {
        searchTerm.value = '';
    }
};
</script>

<template>
    <div :class="wrapperClass">
        <ListboxDropdown
            :open="open"
            :options="props.options"
            :selected-values="model"
            :filter-text="searchTerm"
            :width="props.width"
            :side="props.side"
            :side-offset="props.sideOffset"
            :loading="props.loading"
            :no-results-text="props.noResultsText"
            :close-on-select="false"
            focus-search-on-open
            @update:open="handleOpenChange"
            @select="handleSelect"
        >
            <template #trigger="{ onKeydown }">
                <SelectTrigger :disabled="props.disabled" @keydown="onKeydown">
                    <template #value>
                        <span v-if="selectedOptions.length === 0" class="text-foreground-faint">
                            {{ props.placeholder }}
                        </span>
                        <span
                            v-else
                            class="flex min-w-0 flex-nowrap items-center gap-2 overflow-hidden"
                        >
                            <Badge
                                v-for="option in visibleOptions"
                                :key="String(getOptionValue(option))"
                                class="min-w-0 max-w-full"
                            >
                                <span class="block max-w-full truncate">
                                    {{ getOptionLabel(option) }}
                                </span>
                            </Badge>
                            <Badge v-if="hiddenCount" class="shrink-0">
                                +{{ hiddenCount }}
                            </Badge>
                        </span>
                    </template>
                </SelectTrigger>
            </template>

            <template #header>
                <Input
                    v-model="searchTerm"
                    type="search"
                    :placeholder="props.searchPlaceholder"
                    size="sm"
                    class="h-9"
                    autofocus
                />
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
        <FormError :error="props.error" />
    </div>
</template>
