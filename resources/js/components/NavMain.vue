<script setup lang="ts">
import { computed } from 'vue';
import NavItemLink from '@/components/NavItemLink.vue';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();

const items = computed(() => props.items as NavItem[]);
</script>

<template>
    <div class="space-y-2 px-2">
        <div
            v-if="!props.collapsed"
            class="px-2 text-xs font-semibold uppercase tracking-widest text-foreground-faint"
        >
            Platform
        </div>
        <nav class="space-y-1">
            <NavItemLink
                v-for="item in items"
                :key="item.title"
                :item="item"
                :collapsed="props.collapsed"
                :active="urlIsActive(item.href, page.url)"
            />
        </nav>
    </div>
</template>

