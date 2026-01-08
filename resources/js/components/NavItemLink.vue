<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    collapsed: {
        type: Boolean,
        default: false,
    },
    active: {
        type: Boolean,
        default: false,
    },
    external: {
        type: Boolean,
        default: false,
    },
});

const item = computed(() => props.item as NavItem);
const linkClass = computed(() => [
    'flex items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors',
    props.active
        ? 'bg-secondary-strong text-foreground'
        : 'text-foreground-subtle hover:bg-secondary-hover hover:text-foreground',
    props.collapsed ? 'justify-center px-2' : '',
]);
</script>

<template>
    <component
        :is="props.external ? 'a' : Link"
        :href="props.external ? toUrl(item.href) : item.href"
        :target="props.external ? '_blank' : undefined"
        :rel="props.external ? 'noopener noreferrer' : undefined"
        :class="linkClass"
        :aria-label="props.collapsed ? item.title : undefined"
    >
        <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
        <span v-if="!props.collapsed">{{ item.title }}</span>
    </component>
</template>

