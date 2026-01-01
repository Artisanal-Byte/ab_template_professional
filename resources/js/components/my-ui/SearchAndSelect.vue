<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import Button from '@/components/my-ui/Button.vue';
import Input from '@/components/my-ui/Input.vue';
import ListboxDropdown from '@/components/my-ui/ListboxDropdown.vue';
import { ChevronDown, Plus } from 'lucide-vue-next';

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
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
});

const emit = defineEmits(['create']);

const model = defineModel({
    type: [String, Number, Object],
    default: '',
});

const open = ref(false);
const searchTerm = ref('');

const selectedOption = computed(() =>
    props.options.find(
        (option: any) =>
            (option?.value ?? option?.label ?? '') === model.value,
    ),
);

const canCreate = computed(() => {
    if (!props.allowCreate || !searchTerm.value.trim()) {
        return false;
    }
    const term = searchTerm.value.trim().toLowerCase();
    return !props.options.some(
        (option: any) =>
            String(option?.label ?? '').toLowerCase() === term,
    );
});

const handleSelect = (option: any) => {
    model.value = option?.value ?? option?.label ?? '';
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
    <ListboxDropdown
        v-model:open="open"
        :options="props.options"
        :selected-values="[model]"
        :filter-text="searchTerm"
        :width="props.width"
        :align="props.align"
        :side="props.side"
        :side-offset="props.sideOffset"
        :loading="props.loading"
        :no-results-text="props.noResultsText"
        @select="handleSelect"
    >
        <template #trigger>
            <Button
                variant="outline"
                size="md"
                class="w-full justify-between text-left"
                :disabled="props.disabled"
            >
                <span class="truncate">
                    {{ selectedOption?.label || props.placeholder }}
                </span>
                <ChevronDown class="h-4 w-4 text-foreground/60" />
            </Button>
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
                class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-sm text-foreground/80 transition-colors hover:bg-secondary/60"
                @click="handleCreate"
            >
                <Plus class="h-4 w-4" />
                Add "{{ searchTerm.trim() }}"
            </button>
        </template>
    </ListboxDropdown>
</template>
