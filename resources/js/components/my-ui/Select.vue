<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/my-ui/Button.vue';
import ListboxDropdown from '@/components/my-ui/ListboxDropdown.vue';
import { ChevronDown } from 'lucide-vue-next';

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
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
});

const model = defineModel({
    type: [String, Number, Object],
    default: '',
});

const open = ref(false);

const selectedOption = computed(() =>
    props.options.find(
        (option: any) =>
            (option?.value ?? option?.label ?? '') === model.value,
    ),
);

const handleSelect = (option: any) => {
    model.value = option?.value ?? option?.label ?? '';
};
</script>

<template>
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
    </ListboxDropdown>
</template>
