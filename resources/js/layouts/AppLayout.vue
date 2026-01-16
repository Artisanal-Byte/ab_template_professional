<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    persistNavState?: boolean;
    initialDesktopOpen?: boolean;
    initialMobileOpen?: boolean;
    navDrawerKey?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    persistNavState: true,
    initialDesktopOpen: true,
    initialMobileOpen: false,
});
</script>

<template>
    <AppLayout
        :breadcrumbs="breadcrumbs"
        :persist-nav-state="persistNavState"
        :initial-desktop-open="initialDesktopOpen"
        :initial-mobile-open="initialMobileOpen"
        :nav-drawer-key="navDrawerKey"
    >
        <slot />
    </AppLayout>
</template>
