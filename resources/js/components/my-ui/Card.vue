<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { cn } from '@/lib/utils';

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
});

const slots = useSlots();
const attrs = useAttrs();

const cardClass = computed(() =>
    cn(
        'rounded-lg border border-border bg-card text-card-foreground shadow-sm',
        attrs.class,
    ),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});

const hasHeader = computed(
    () =>
        Boolean(slots.header) ||
        Boolean(slots.title) ||
        Boolean(slots.description),
);
const hasFooter = computed(() => Boolean(slots.footer));
const hasContent = computed(() => Boolean(slots.default));

const contentClass = computed(() =>
    cn(
        'p-6',
        hasHeader.value ? 'pt-0' : '',
        hasFooter.value ? 'pb-0' : '',
        props.contentClass,
    ),
);
</script>

<template>
    <component :is="props.as" v-bind="boundAttrs" :class="cardClass">
        <div v-if="hasHeader" :class="cn('flex flex-col gap-1.5 p-6', props.headerClass)">
            <slot name="header">
                <div v-if="$slots.title" class="text-lg font-semibold leading-none tracking-tight">
                    <slot name="title" />
                </div>
                <div v-if="$slots.description" class="text-sm text-foreground/70">
                    <slot name="description" />
                </div>
            </slot>
        </div>

        <div v-if="hasContent" :class="contentClass">
            <slot />
        </div>

        <div v-if="hasFooter" :class="cn('flex items-center gap-2 p-6 pt-0', props.footerClass)">
            <slot name="footer" />
        </div>
    </component>
</template>
