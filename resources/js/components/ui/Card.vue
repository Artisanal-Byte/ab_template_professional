<script setup lang="ts">
import { computed, ref, useAttrs, useSlots } from 'vue';
import Icon from '@/components/Icon.vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';
import { cardBase } from '@/lib/uiClass';

// Slots:
// - header: Custom header wrapper.
// - title: Card title.
// - description: Card description.
// - default: Card body content.
// - footer: Card footer content.
const props = defineProps({
    as: {
        type: [String, Object],
        default: 'div',
    },
    headerClass: {
        type: String,
        default: '',
    },
    contentClass: {
        type: String,
        default: '',
    },
    footerClass: {
        type: String,
        default: '',
    },
    collapsible: {
        type: Boolean,
        default: false,
    },
    startCollapsed: {
        type: Boolean,
        default: false,
    },
});

const slots = useSlots();
const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const cardClass = computed(() => cardBase(attrsSplit.value.className));

const boundAttrs = computed(() => attrsSplit.value.rest);

const hasHeader = computed(
    () =>
        Boolean(slots.header) ||
        Boolean(slots.title) ||
        Boolean(slots.description),
);
const hasFooter = computed(() => Boolean(slots.footer));
const hasContent = computed(() => Boolean(slots.default));
const isCollapsed = ref(props.startCollapsed);

const showBody = computed(() => !props.collapsible || !isCollapsed.value);

const headerClass = computed(() =>
    cn(
        'flex flex-col gap-1.5 px-6 p-4',
        props.collapsible && isCollapsed.value ? '' : 'border-b border-border',
        props.headerClass,
    ),
);

const contentClass = computed(() =>
    cn(
        'py-4 px-6',
        props.contentClass,
    ),
);

function toggleCollapsed(): void {
    if (!props.collapsible) {
        return;
    }

    isCollapsed.value = !isCollapsed.value;
}
</script>

<template>
    <component :is="props.as" v-bind="boundAttrs" :class="cardClass">
        <div v-if="hasHeader" :class="headerClass">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <slot name="header">
                        <div v-if="$slots.title" class="text-lg font-semibold leading-none tracking-tight">
                            <slot name="title" />
                        </div>
                        <div v-if="$slots.description" class="text-sm text-foreground-subtle">
                            <slot name="description" />
                        </div>
                    </slot>
                </div>

                <div v-if="$slots.actions || props.collapsible" class="flex items-center gap-2">
                    <slot name="actions" />
                    <button
                        v-if="props.collapsible"
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-border text-foreground-subtle transition-colors hover:bg-secondary-soft hover:text-foreground"
                        @click="toggleCollapsed"
                    >
                        <Icon :name="isCollapsed ? 'lucide:chevron-down' : 'lucide:chevron-up'" class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>

        <Transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-[1400px] opacity-100"
            leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="max-h-[1400px] opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-if="showBody" class="overflow-hidden">
                <div v-if="hasContent" :class="contentClass">
                    <slot />
                </div>

                <div v-if="hasFooter" :class="cn('flex items-center gap-2 p-6 pt-0', props.footerClass)">
                    <slot name="footer" />
                </div>
            </div>
        </Transition>
    </component>
</template>
