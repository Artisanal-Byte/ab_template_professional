<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue';
import {
    PopoverContent,
    PopoverPortal,
    PopoverRoot,
    PopoverTrigger,
} from 'reka-ui';
import { Check } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

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
    contentClass: {
        type: String,
        default: '',
    },
    maxHeight: {
        type: String,
        default: '18rem',
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

const contentRef = ref<HTMLElement | null>(null);
const activeIndex = ref(0);

const widthClasses = {
    xs: 'w-32',
    sm: 'w-40',
    md: 'w-56',
    lg: 'w-72',
    xl: 'w-80',
    full: 'w-full min-w-0',
};

const widthStyles = {
    xs: '8rem',
    sm: '10rem',
    md: '14rem',
    lg: '18rem',
    xl: '20rem',
    full: '100%',
};

const contentClasses = computed(() =>
    cn(
        'z-50 rounded-lg border border-border bg-card text-card-foreground shadow-lg',
        widthClasses[props.width] || widthClasses.md,
        'data-[state=open]:animate-in data-[state=open]:fade-in-0 data-[state=open]:zoom-in-95',
        'data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95',
        props.contentClass,
    ),
);

const contentStyle = computed(() => {
    if (props.width === 'full') {
        return {
            width: 'var(--reka-popover-trigger-width)',
            minWidth: 'var(--reka-popover-trigger-width)',
        };
    }

    const width = widthStyles[props.width] || widthStyles.md;
    return {
        width,
        minWidth: width,
    };
});

const getOptionValue = (option: any) =>
    option?.value ?? option?.label ?? '';

const filteredOptions = computed(() => {
    if (!props.filterText) {
        return props.options;
    }

    const term = props.filterText.toLowerCase();
    return props.options.filter((option: any) => {
        const label = String(option?.label ?? '').toLowerCase();
        const description = String(option?.description ?? '').toLowerCase();
        return label.includes(term) || description.includes(term);
    });
});

const groupedOptions = computed(() => {
    const groups = new Map<string, any[]>();
    filteredOptions.value.forEach((option: any) => {
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
    filteredOptions.value.filter((option: any) => !option?.disabled),
);

const activeOption = computed(
    () => flatOptions.value[activeIndex.value] || null,
);

const isSelected = (option: any) => {
    const value = getOptionValue(option);
    return props.selectedValues.some((selected: any) => selected === value);
};

const handleSelect = (option: any) => {
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
    () => [open.value, props.filterText],
    async () => {
        if (!open.value) {
            activeIndex.value = 0;
            return;
        }
        await nextTick();
        contentRef.value?.focus();
    },
);
</script>

<template>
    <PopoverRoot v-model:open="open">
        <PopoverTrigger as-child>
            <slot name="trigger" />
        </PopoverTrigger>
        <PopoverPortal>
            <PopoverContent
                ref="contentRef"
                :side="props.side"
                :align="props.align"
                :side-offset="props.sideOffset"
                :class="contentClasses"
                :style="contentStyle"
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
                    <div v-if="props.loading" class="py-4 text-center text-sm text-foreground/60">
                        <slot name="loading">Loading...</slot>
                    </div>
                    <div
                        v-else-if="!filteredOptions.length"
                        class="py-4 text-center text-sm text-foreground/60"
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
                                class="px-2 pt-2 text-xs font-semibold uppercase tracking-wide text-foreground/50"
                            >
                                {{ group.group }}
                            </div>
                            <button
                                v-for="option in group.options"
                                :key="String(getOptionValue(option))"
                                type="button"
                                :disabled="option.disabled"
                                class="flex w-full items-start gap-2 rounded-md px-2 py-1.5 text-left text-sm transition-colors"
                                :class="[
                                    option.disabled
                                        ? 'cursor-not-allowed opacity-50'
                                        : 'hover:bg-secondary/50',
                                    isSelected(option)
                                        ? 'bg-secondary/60 text-foreground'
                                        : 'text-foreground/80',
                                    activeOption === option
                                        ? 'ring-1 ring-primary/30'
                                        : '',
                                ]"
                                @click="handleSelect(option)"
                            >
                                <span class="mt-0.5 text-foreground/60">
                                    <Check v-if="isSelected(option)" class="h-4 w-4" />
                                </span>
                                <span class="flex-1">
                                    <span class="block font-medium">{{ option.label }}</span>
                                    <span
                                        v-if="option.description"
                                        class="block text-xs text-foreground/60"
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
            </PopoverContent>
        </PopoverPortal>
    </PopoverRoot>
</template>
