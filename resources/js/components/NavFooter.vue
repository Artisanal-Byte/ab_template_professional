<script setup lang="ts">
import { computed } from 'vue';
import NavItemLink from '@/components/NavItemLink.vue';
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

const items = computed(() => props.items as NavItem[]);
</script>

<template>
    <div :class="props.class || ''" class="space-y-1 px-2">
        <NavItemLink
            v-for="item in items"
            :key="item.title"
            :item="item"
            :collapsed="props.collapsed"
        />
    </div>
</template>
