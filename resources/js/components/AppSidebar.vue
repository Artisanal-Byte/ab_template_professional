<script setup lang="ts">
// Sidebar composition: logo header, main nav, and footer/user actions.
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { externalNavItems, mainNavItems } from '@/config/navConfig';
import { dashboard as adminDashboard } from '@/routes/admin';
import { dashboard as platformDashboard } from '@/routes/platform';
import { dashboard as tenantDashboard } from '@/routes/tenant';
import { Link, usePage } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const roles = (page.props.auth?.roles ?? []) as string[];
const currentTenantId =
    page.props.currentTenant?.id ?? page.props.tenantMemberships?.[0]?.tenant_id ?? null;
const tenantMemberships = (page.props.tenantMemberships ?? []) as Array<{
    tenant_id: string;
    membership_role: string;
}>;
const tenantRole =
    tenantMemberships.find((membership) => membership.tenant_id === currentTenantId)
        ?.membership_role ?? null;
const mainItems = mainNavItems(roles, tenantRole);
const footerItems = externalNavItems();
const homeRoute = roles.includes('web_admin')
    ? platformDashboard
    : roles.includes('admin')
      ? adminDashboard
      : tenantDashboard;
</script>

<template>
    <div
        class="flex h-16 items-center gap-2 border-b border-border px-4"
        :class="props.collapsed ? 'justify-center px-2' : ''"
    >
        <Link :href="homeRoute()" class="flex items-center gap-2">
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
