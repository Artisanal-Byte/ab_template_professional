<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import Input from '@/components/ui/Input.vue';
import ListboxDropdown from '@/components/ui/ListboxDropdown.vue';
import SelectTrigger from '@/components/ui/SelectTrigger.vue';
import Icon from '@/components/Icon.vue';
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
        default: 'Search and select',
    },
    searchPlaceholder: {
        type: String,
        default: 'Search...',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    allowCreate: {
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

const emit = defineEmits(['create']);

const model = defineModel({
    type: [String, Number, Object],
    default: '',
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

const selectedOption = computed(() =>
    options.value.find(
        (option) =>
            getOptionValue(option) === model.value,
    ),
);

const canCreate = computed(() => {
    if (!props.allowCreate || !searchTerm.value.trim()) {
        return false;
    }
    const term = searchTerm.value.trim().toLowerCase();
    return !options.value.some(
        (option) =>
            String(option?.label ?? '').toLowerCase() === term,
    );
});

const handleSelect = (option: SelectOption) => {
    model.value = getOptionValue(option);
    searchTerm.value = '';
};

const handleCreate = () => {
    if (!canCreate.value) {
        return;
    }
    emit('create', searchTerm.value.trim());
    open.value = false;
    searchTerm.value = '';
};

watch(open, (value) => {
    if (!value) {
        searchTerm.value = '';
    }
});
</script>

<template>
    <div :class="wrapperClass">
        <ListboxDropdown
            v-model:open="open"
            :options="props.options"
            :selected-values="[model]"
            :filter-text="searchTerm"
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

            <template #footer v-if="props.allowCreate && canCreate">
                <button
                    type="button"
                    class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-sm text-foreground-muted transition-colors hover:bg-secondary-active"
                    @click="handleCreate"
                >
                    <Icon name="lucide:plus" class="h-4 w-4" />
                    Add "{{ searchTerm.trim() }}"
                </button>
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
