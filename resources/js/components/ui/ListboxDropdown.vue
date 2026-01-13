<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue';
import PopoverBase from '@/components/ui/PopoverBase.vue';
import { Check } from 'lucide-vue-next';
import { cn } from '@/lib/utils';
import { getOptionValue } from '@/composables/useSelectDisplay';
import type { SelectOption } from '@/types';

// Slots:
// - trigger: Trigger element.
// - header: Optional header content (e.g., search input).
// - footer: Optional footer content (e.g., create action).
// - loading: Custom loading state.
// - empty: Custom empty state.
// - default: Option list content (internal rendering).
const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    selectedValues: {
        type: Array,
        default: () => [],
    },
    filterText: {
        type: String,
        default: '',
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
    contentClass: {
        type: String,
        default: '',
    },
    maxHeight: {
        type: String,
        default: '18rem',
    },
    matchTriggerWidth: {
        type: Boolean,
        default: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    noResultsText: {
        type: String,
        default: 'No results',
    },
    closeOnSelect: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['select']);

const open = defineModel('open', {
    type: Boolean,
    default: undefined,
});

const contentRef = ref<any>(null);
const activeIndex = ref(0);

const contentClasses = computed(() =>
    cn(
        'z-50 rounded-lg border border-border bg-card text-card-foreground shadow-lg',
        'p-0',
        'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
        props.contentClass,
    ),
);

const options = computed(() => props.options as SelectOption[]);
const selectedValues = computed(
    () => props.selectedValues as Array<string | number>,
);

const getOptionValueSafe = (option: SelectOption) =>
    getOptionValue(option);

const filteredOptions = computed(() => {
    if (!props.filterText) {
        return options.value;
    }

    const term = props.filterText.toLowerCase();
    return options.value.filter((option) => {
        const label = String(option?.label ?? '').toLowerCase();
        const description = String(option?.description ?? '').toLowerCase();
        return label.includes(term) || description.includes(term);
    });
});

const groupedOptions = computed(() => {
    const groups = new Map<string, SelectOption[]>();
    filteredOptions.value.forEach((option) => {
        const group = option?.group || '';
        if (!groups.has(group)) {
            groups.set(group, []);
        }
        groups.get(group)?.push(option);
    });

    return Array.from(groups.entries()).map(([group, options]) => ({
        group,
        options,
    }));
});

const flatOptions = computed(() =>
    filteredOptions.value.filter((option) => !option?.disabled),
);

const activeOption = computed(
    () => flatOptions.value[activeIndex.value] || null,
);

const isSelected = (option: SelectOption) => {
    const value = getOptionValueSafe(option);
    return selectedValues.value.some((selected) => selected === value);
};

const handleSelect = (option: SelectOption) => {
    if (!option || option.disabled) {
        return;
    }

    emit('select', option);

    if (props.closeOnSelect) {
        open.value = false;
    }
};

const handleKeydown = (event: KeyboardEvent) => {
    if (!flatOptions.value.length) {
        if (event.key === 'Escape') {
            open.value = false;
        }
        return;
    }

    switch (event.key) {
        case 'ArrowDown':
            event.preventDefault();
            activeIndex.value =
                (activeIndex.value + 1) % flatOptions.value.length;
            break;
        case 'ArrowUp':
            event.preventDefault();
            activeIndex.value =
                (activeIndex.value - 1 + flatOptions.value.length) %
                flatOptions.value.length;
            break;
        case 'Home':
            event.preventDefault();
            activeIndex.value = 0;
            break;
        case 'End':
            event.preventDefault();
            activeIndex.value = flatOptions.value.length - 1;
            break;
        case 'Enter':
            event.preventDefault();
            handleSelect(activeOption.value);
            break;
        case 'Escape':
            event.preventDefault();
            open.value = false;
            break;
        default:
            break;
    }
};

watch(
    () => open.value,
    async (value) => {
        if (!value) {
            activeIndex.value = 0;
            return;
        }
        await nextTick();
        const target = contentRef.value?.$el ?? contentRef.value;
        target?.focus?.();
    },
);
</script>

<template>
    <PopoverBase
        v-model:open="open"
        :side="props.side"
        :side-offset="props.sideOffset"
        :width="props.width"
        :content-class="contentClasses"
        :match-trigger-width="props.matchTriggerWidth"
    >
        <template #trigger>
            <slot name="trigger" />
        </template>
        <div
            ref="contentRef"
            tabindex="0"
            @keydown="handleKeydown"
        >
            <div v-if="$slots.header" class="border-b border-border p-2">
                <slot name="header" />
            </div>

            <div
                class="p-2"
                :style="{ maxHeight: props.maxHeight, overflowY: 'auto' }"
            >
                <div v-if="props.loading" class="py-4 text-center text-sm text-foreground-faint">
                    <slot name="loading">Loading...</slot>
                </div>
                <div
                    v-else-if="!filteredOptions.length"
                    class="py-4 text-center text-sm text-foreground-faint"
                >
                    <slot name="empty">{{ props.noResultsText }}</slot>
                </div>
                    <div v-else class="grid gap-1">
                        <div
                            v-for="group in groupedOptions"
                            :key="group.group || 'default'"
                            class="grid gap-1"
                        >
                            <div
                                v-if="group.group"
                                class="px-2 pt-2 text-xs font-semibold uppercase tracking-wide text-foreground-disabled"
                            >
                                {{ group.group }}
                            </div>
                            <button
                                v-for="option in group.options"
                                :key="String(getOptionValueSafe(option))"
                                type="button"
                                :disabled="option.disabled"
                                class="flex w-full items-start gap-2 rounded-md px-2 py-1.5 text-left text-sm transition-colors"
                                :class="[
                                    option.disabled
                                        ? 'cursor-not-allowed opacity-50'
                                        : 'hover:bg-secondary-hover',
                                    isSelected(option)
                                        ? 'bg-secondary-active text-foreground'
                                        : 'text-foreground-muted',
                                    activeOption === option
                                        ? 'ring-1 ring-focus-ring'
                                        : '',
                                ]"
                                @click="handleSelect(option)"
                            >
                                <span class="mt-0.5 text-foreground-faint">
                                    <Check v-if="isSelected(option)" class="h-4 w-4" />
                                </span>
                                <span class="flex-1">
                                    <span class="block break-words font-medium">
                                        {{ option.label }}
                                    </span>
                                    <span
                                        v-if="option.description"
                                        class="block break-words text-xs text-foreground-faint"
                                    >
                                        {{ option.description }}
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
            </div>

            <div v-if="$slots.footer" class="border-t border-border p-2">
                <slot name="footer" />
            </div>
        </div>
    </PopoverBase>
</template>
