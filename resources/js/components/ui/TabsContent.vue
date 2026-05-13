<script setup lang="ts">
import { TabsContent } from 'reka-ui';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

// Slots:
// - default: Tab content panel.
const props = defineProps({
    value: {
        type: String,
        required: true,
    },
    tabContentClass: {
        type: String,
        default: '',
    },
});

const tabContentClasses = computed(() =>
    cn(
        'mt-4',
        props.tabContentClass,
    ),
);
</script>

<template>
    <TabsContent
        :class="tabContentClasses"
        :value="value"
    >
        <slot />
    </TabsContent>
</template>
