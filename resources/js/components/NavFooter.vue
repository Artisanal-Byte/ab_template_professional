<script setup lang="ts">
import { toUrl } from '@/lib/utils';
import { type NavItem } from '@/types';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    collapsed: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div :class="props.class || ''" class="space-y-1 px-2">
        <a
            v-for="item in items"
            :key="item.title"
            :href="toUrl(item.href)"
            target="_blank"
            rel="noopener noreferrer"
            class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-foreground/70 transition-colors hover:bg-secondary/50 hover:text-foreground"
            :class="props.collapsed ? 'justify-center px-2' : ''"
            :aria-label="props.collapsed ? item.title : undefined"
        >
            <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
            <span v-if="!props.collapsed">{{ item.title }}</span>
        </a>
    </div>
</template>
