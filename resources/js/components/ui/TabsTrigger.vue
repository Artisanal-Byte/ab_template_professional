<script setup lang="ts">
import { TabsTrigger } from 'reka-ui';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

// Slots:
// - default: Tab trigger content.
const props = defineProps({
    value: {
        type: String,
        required: true,
    },
    tabTriggerClass: {
        type: String,
        default: '',
    },
});

const tabTriggerClasses = computed(() =>
    cn(
        'flex items-center rounded-md px-3.5 py-1.5 text-sm font-medium transition-colors',
        'text-foreground-faint hover:bg-secondary-active hover:text-foreground',
        'data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm',
        props.tabTriggerClass,
    ),
);
</script>

<template>
    <TabsTrigger
        :class="tabTriggerClasses"
        :value="value"
    >
        <slot />
    </TabsTrigger>
</template>
