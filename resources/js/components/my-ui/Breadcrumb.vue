<script setup lang="ts">
import { computed, useAttrs, useSlots } from 'vue';
import { ChevronRight, MoreHorizontal } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});

const slots = useSlots();
const attrs = useAttrs();

const breadcrumbClass = computed(() =>
    cn('text-sm text-foreground/70', attrs.class),
);

const boundAttrs = computed(() => {
    const { class: _class, ...rest } = attrs;
    return rest;
});

const hasItems = computed(() => props.items.length > 0);
</script>

<template>
    <nav aria-label="Breadcrumb" v-bind="boundAttrs" :class="breadcrumbClass">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm">
            <template v-if="hasItems">
                <template v-for="(item, index) in props.items" :key="index">
                    <li class="inline-flex items-center gap-1.5">
                        <slot name="item" :item="item">
                            <template v-if="item.ellipsis">
                                <slot name="ellipsis">
                                    <span
                                        class="flex h-8 w-8 items-center justify-center text-foreground/60"
                                        aria-hidden="true"
                                        role="presentation"
                                    >
                                        <MoreHorizontal class="h-4 w-4" />
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
                        v-if="index < props.items.length - 1"
                        aria-hidden="true"
                        role="presentation"
                        class="text-foreground/40 [&>svg]:h-3.5 [&>svg]:w-3.5"
                    >
                        <slot name="separator">
                            <ChevronRight />
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
