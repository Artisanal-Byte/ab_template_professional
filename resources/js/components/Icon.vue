<script setup lang="ts">
import { computed } from 'vue';
import { cn } from '@/lib/utils';
import * as icons from 'lucide-vue-next';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: '',
    },
    size: {
        type: [Number, String],
        default: 16,
    },
    color: {
        type: String,
        default: '',
    },
    strokeWidth: {
        type: [Number, String],
        default: 2,
    },
});

const className = computed(() => cn('h-4 w-4', props.class));

const icon = computed(() => {
    const iconName = props.name.charAt(0).toUpperCase() + props.name.slice(1);
    return (icons as Record<string, any>)[iconName];
});
</script>

<template>
    <component
        :is="icon"
        :class="className"
        :size="size"
        :stroke-width="strokeWidth"
        :color="color"
    />
</template>
