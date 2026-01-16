<script setup lang="ts">
import { computed } from 'vue';
import NavItemLink from '@/components/NavItemLink.vue';
import InlineDropdown from '@/components/ui/InlineDropdown.vue';
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

const items = computed(() => props.items as NavItem[]);

const primaryItem = computed(() => items.value[0]);
const remainingItems = computed(() => items.value.slice(1));

const inlineMenuItems = [
    { label: 'Quick action one', href: '#' },
    { label: 'Quick action two', href: '#' },
    { label: 'Quick action three', href: '#' },
];

const demoItem: NavItem = {
    title: 'Extra item',
    href: '#',
};
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
                v-if="primaryItem"
                :item="primaryItem"
                :collapsed="props.collapsed"
                :active="urlIsActive(primaryItem.href, page.url)"
            />

            <InlineDropdown
                v-if="!props.collapsed"
                width="full"
                trigger-label="Quick actions"
                trigger-variant="ghost"
                trigger-size="sm"
                trigger-class="h-auto px-3 py-2 text-sm font-normal text-foreground-subtle hover:bg-secondary-hover hover:text-foreground"
                content-class="border border-border-subtle bg-background"
            >
                <div class="grid gap-1">
                    <Link
                        v-for="item in inlineMenuItems"
                        :key="item.label"
                        :href="item.href"
                        class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-foreground-subtle transition-colors hover:bg-secondary-hover hover:text-foreground"
                    >
                        {{ item.label }}
                    </Link>
                </div>
            </InlineDropdown>

            <NavItemLink
                v-if="!props.collapsed"
                :item="demoItem"
                :collapsed="false"
                :active="false"
            />

            <NavItemLink
                v-for="item in remainingItems"
                :key="item.title"
                :item="item"
                :collapsed="props.collapsed"
                :active="urlIsActive(item.href, page.url)"
            />
        </nav>
    </div>
</template>
