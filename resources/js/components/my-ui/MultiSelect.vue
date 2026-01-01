<script setup lang="ts">
import { computed, ref } from 'vue';
import Badge from '@/components/my-ui/Badge.vue';
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
});

const model = defineModel({
    type: Array,
    default: () => [],
});

const open = ref(false);

const selectedOptions = computed(() =>
    props.options.filter((option: any) =>
        model.value.includes(option?.value ?? option?.label ?? ''),
    ),
);

const handleSelect = (option: any) => {
    const value = option?.value ?? option?.label ?? '';
    if (model.value.includes(value)) {
        model.value = model.value.filter((item: any) => item !== value);
        return;
    }
    model.value = [...model.value, value];
};
</script>

<template>
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
            <Button
                variant="outline"
                size="md"
                class="w-full justify-between gap-2 text-left"
                :disabled="props.disabled"
            >
                <span class="flex flex-wrap items-center gap-2">
                    <span
                        v-if="selectedOptions.length === 0"
                        class="text-foreground/60"
                    >
                        {{ props.placeholder }}
                    </span>
                    <Badge
                        v-for="option in selectedOptions"
                        :key="String(option?.value ?? option?.label ?? '')"
                    >
                        {{ option.label }}
                    </Badge>
                </span>
                <span class="flex items-center gap-2">
                    <span
                        v-if="selectedOptions.length"
                        class="rounded-full bg-secondary px-2 py-0.5 text-xs font-semibold text-foreground"
                    >
                        {{ selectedOptions.length }}
                    </span>
                    <ChevronDown class="h-4 w-4 text-foreground/60" />
                </span>
            </Button>
        </template>
    </ListboxDropdown>
</template>
