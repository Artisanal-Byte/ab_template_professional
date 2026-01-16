<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

// Slots:
// - item: Custom item rendering.
// - separator: Custom separator icon/content.
// - ellipsis: Custom ellipsis rendering.
// - default: Custom content when no items are provided.
type BreadcrumbItem = {
    label: string;
    href?: string;
    current?: boolean;
    ellipsis?: boolean;
    as?: string | object;
};

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const breadcrumbClass = computed(() =>
    cn('text-sm text-foreground-subtle', attrsSplit.value.className),
);

const boundAttrs = computed(() => attrsSplit.value.rest);

const items = computed(() => props.items as BreadcrumbItem[]);
const hasItems = computed(() => items.value.length > 0);
</script>

<template>
    <nav aria-label="Breadcrumb" v-bind="boundAttrs" :class="breadcrumbClass">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm">
            <template v-if="hasItems">
                <template v-for="(item, index) in items" :key="index">
                    <li class="inline-flex items-center gap-1.5">
                        <slot name="item" :item="item">
                            <template v-if="item.ellipsis">
                                <slot name="ellipsis">
                                    <span
                                        class="flex h-8 w-8 items-center justify-center text-foreground-faint"
                                        aria-hidden="true"
                                        role="presentation"
                                    >
                                        <IconLucideMoreHorizontal class="h-4 w-4" />
                                        <span class="sr-only">More</span>
                                    </span>
                                </slot>
                            </template>
                            <template v-else-if="item.current">
                                <span aria-current="page" class="text-foreground">
                                    {{ item.label }}
                                </span>
                            </template>
                            <template v-else>
                                <component
                                    :is="item.as || 'a'"
                                    :href="item.href"
                                    class="transition-colors hover:text-foreground"
                                >
                                    {{ item.label }}
                                </component>
                            </template>
                        </slot>
                    </li>
                    <li
                        v-if="index < items.length - 1"
                        aria-hidden="true"
                        role="presentation"
                        class="text-foreground-disabled [&>svg]:h-3.5 [&>svg]:w-3.5"
                    >
                        <slot name="separator">
                            <IconLucideChevronRight />
                        </slot>
                    </li>
                </template>
            </template>
            <template v-else>
                <slot />
            </template>
        </ol>
    </nav>
</template>
