<script setup lang="ts">
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

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
</script>

<template>
    <div class="space-y-2 px-2">
        <div
            v-if="!props.collapsed"
            class="px-2 text-xs font-semibold uppercase tracking-widest text-foreground/60"
        >
            Platform
        </div>
        <nav class="space-y-1">
            <Link
                v-for="item in items"
                :key="item.title"
                :href="item.href"
                class="flex items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors"
                :class="[
                    urlIsActive(item.href, page.url)
                        ? 'bg-secondary/70 text-foreground'
                        : 'text-foreground/70 hover:bg-secondary/50 hover:text-foreground',
                    props.collapsed ? 'justify-center px-2' : '',
                ]"
                :aria-label="props.collapsed ? item.title : undefined"
            >
                <component
                    v-if="item.icon"
                    :is="item.icon"
                    class="h-4 w-4"
                />
                <span v-if="!props.collapsed">{{ item.title }}</span>
            </Link>
        </nav>
    </div>
</template>
