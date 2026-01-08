<script setup lang="ts">
// Sidebar composition: logo header, main nav, and footer/user actions.
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { externalNavItems, mainNavItems } from '@/config/navConfig';
import { dashboard } from '@/routes';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const mainItems = mainNavItems();
const footerItems = externalNavItems();
</script>

<template>
    <div
        class="flex h-16 items-center gap-2 border-b border-border px-4"
        :class="props.collapsed ? 'justify-center px-2' : ''"
    >
        <Link :href="dashboard()" class="flex items-center gap-2">
            <AppLogo
                v-if="props.collapsed"
                :show-text="false"
                size="sm"
                container-class="bg-primary text-primary-foreground"
            />
            <AppLogo v-else />
        </Link>
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto py-4">
        <NavMain :items="mainItems" :collapsed="props.collapsed" />
    </div>

    <div class="mt-auto space-y-4 border-t border-border py-4">
        <NavFooter :items="footerItems" :collapsed="props.collapsed" />
        <NavUser :collapsed="props.collapsed" />
    </div>
</template>
