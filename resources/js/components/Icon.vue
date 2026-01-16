<script setup lang="ts">
import { computed } from 'vue';
import { Icon as IconifyIcon } from '@iconify/vue';
import { cn } from '@/lib/utils';

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
</script>

<template>
    <component
        :is="IconifyIcon"
        :class="className"
        :icon="name"
        :width="size"
        :height="size"
        :stroke-width="strokeWidth"
        :color="color"
    />
</template>
