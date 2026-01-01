<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import NavDrawer from '@/components/NavDrawer.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const NAV_DRAWER_KEY = 'nav-drawer-desktop';
const isDesktopNavOpen = ref(true);
const isMobileNavOpen = ref(false);

if (typeof window !== 'undefined') {
    const stored = window.localStorage.getItem(NAV_DRAWER_KEY);
    if (stored === 'collapsed') {
        isDesktopNavOpen.value = false;
    }
}

watch(
    isDesktopNavOpen,
    (value) => {
        if (typeof window === 'undefined') {
            return;
        }
        window.localStorage.setItem(
            NAV_DRAWER_KEY,
            value ? 'open' : 'collapsed',
        );
    },
    { immediate: true },
);
</script>

<template>
    <AppShell variant="sidebar">
        <NavDrawer
            v-model:desktopOpen="isDesktopNavOpen"
            v-model:mobileOpen="isMobileNavOpen"
        >
            <template #default="{ collapsed }">
                <AppSidebar :collapsed="collapsed" />
            </template>
        </NavDrawer>
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader
                :breadcrumbs="props.breadcrumbs"
                @toggle-desktop="isDesktopNavOpen = !isDesktopNavOpen"
                @toggle-mobile="isMobileNavOpen = true"
            />
            <slot />
        </AppContent>
    </AppShell>
</template>
