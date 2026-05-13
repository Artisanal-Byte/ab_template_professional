<script setup lang="ts">
import { TabsList } from 'reka-ui';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

// Slots:
// - default: Tab trigger buttons.
const props = defineProps({
    orientation: {
        type: String,
        default: 'horizontal',
    },
    loop: {
        type: Boolean,
        default: undefined,
    },
    tabListClass: {
        type: String,
        default: '',
    },
});

const tabListClasses = computed(() =>
    cn(
        'inline-flex items-center gap-1 rounded-lg border border-border bg-secondary-subtle p-1',
        props.orientation === 'horizontal'
            ? ''
            : 'flex-col',
        props.tabListClass,
    ),
);
</script>

<template>
    <TabsList
        :class="tabListClasses"
        v-model:orientation="orientation"
        :loop="loop"
    >
        <slot />
    </TabsList>
</template>
