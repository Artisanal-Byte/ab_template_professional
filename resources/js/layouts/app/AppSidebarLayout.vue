<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import NavDrawer from '@/components/NavDrawer.vue';
import { ref, watch } from 'vue';

const DEFAULT_NAV_DRAWER_KEY = 'nav-drawer-desktop';

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
    persistNavState: {
        type: Boolean,
        default: true,
    },
    initialDesktopOpen: {
        type: Boolean,
        default: true,
    },
    initialMobileOpen: {
        type: Boolean,
        default: false,
    },
    navDrawerKey: {
        type: String,
        default: DEFAULT_NAV_DRAWER_KEY,
    },
});

const isDesktopNavOpen = ref(props.initialDesktopOpen);
const isMobileNavOpen = ref(props.initialMobileOpen);

if (typeof window !== 'undefined') {
    if (props.persistNavState && props.navDrawerKey) {
        const stored = window.localStorage.getItem(props.navDrawerKey);
        if (stored === 'collapsed') {
            isDesktopNavOpen.value = false;
        }
    }
}

watch(
    isDesktopNavOpen,
    (value) => {
        if (typeof window === 'undefined') {
            return;
        }
        if (!props.persistNavState || !props.navDrawerKey) {
            return;
        }
        window.localStorage.setItem(
            props.navDrawerKey,
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
