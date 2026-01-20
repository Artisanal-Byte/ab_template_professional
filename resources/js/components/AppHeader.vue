<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import AppBreadcrumbs from '@/components/AppBreadcrumbs.vue';
import Avatar from '@/components/ui/Avatar.vue';
import Button from '@/components/ui/Button.vue';
import Dialog from '@/components/ui/Dialog.vue';
import DropdownMenu from '@/components/ui/DropdownMenu.vue';
import Tooltip from '@/components/ui/Tooltip.vue';
import Icon from '@/components/Icon.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import { toUrl, urlIsActive } from '@/lib/utils';
import { externalNavItems, mainNavItems } from '@/config/navConfig';
import { dashboard as adminDashboard } from '@/routes/admin';
import { dashboard as platformDashboard } from '@/routes/platform';
import { dashboard as tenantDashboard } from '@/routes/tenant';
import type { BreadcrumbItem, NavItem } from '@/types';
import { InertiaLinkProps, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const auth = computed(() => page.props.auth);
const breadcrumbs = computed(() => props.breadcrumbs as BreadcrumbItem[]);

const isCurrentRoute = computed(
    () => (url: NonNullable<InertiaLinkProps['href']>) =>
        urlIsActive(url, page.url),
);

const activeItemStyles = computed(
    () => (url: NonNullable<InertiaLinkProps['href']>) =>
        isCurrentRoute.value(toUrl(url))
            ? 'border-primary text-foreground'
            : '',
);

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
const mainItems: NavItem[] = mainNavItems(roles, tenantRole);
const rightItems: NavItem[] = externalNavItems();
const homeRoute =
    roles.includes('web_admin')
        ? platformDashboard
        : roles.includes('admin')
          ? adminDashboard
          : tenantDashboard;

const isExternalLink = (item: NavItem) =>
    typeof item.href === 'string' && item.href.startsWith('http');
</script>

<template>
    <div>
        <div class="border-b border-border-muted">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu -->
                <div class="lg:hidden">
                    <Dialog
                        :show-close="false"
                        content-class="left-0 top-0 h-full w-[300px] max-w-[90vw] translate-x-0 translate-y-0 rounded-none p-6"
                    >
                        <template #trigger>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="mr-2 h-9 w-9"
                            >
                                <Icon name="lucide:menu" class="h-5 w-5" />
                            </Button>
                        </template>
                        <span class="sr-only">Navigation Menu</span>
                        <div class="mb-4 flex items-center justify-start">
                            <AppLogo
                                :show-text="false"
                                size="sm"
                                container-class="bg-primary text-primary-foreground"
                            />
                        </div>
                        <div
                            class="flex h-full flex-1 flex-col justify-between space-y-4 py-2"
                        >
                            <nav class="-mx-3 space-y-1">
                                <Link
                                    v-for="item in mainItems"
                                    :key="item.title"
                                    :href="item.href"
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium text-foreground-muted hover:bg-secondary-subtle hover:text-foreground"
                                    :class="activeItemStyles(item.href)"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="Icon"
                                        :name="item.icon"
                                        class="h-5 w-5"
                                    />
                                    {{ item.title }}
                                </Link>
                            </nav>
                            <div class="flex flex-col space-y-4">
                                <component
                                    :is="isExternalLink(item) ? 'a' : Link"
                                    v-for="item in rightItems"
                                    :key="item.title"
                                    :href="isExternalLink(item) ? toUrl(item.href) : item.href"
                                    :target="isExternalLink(item) ? '_blank' : undefined"
                                    :rel="isExternalLink(item) ? 'noopener noreferrer' : undefined"
                                    class="flex items-center space-x-2 text-sm font-medium text-foreground-subtle hover:text-foreground"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="Icon"
                                        :name="item.icon"
                                        class="h-5 w-5"
                                    />
                                    <span>{{ item.title }}</span>
                                </component>
                            </div>
                        </div>
                    </Dialog>
                </div>

                <Link :href="homeRoute()" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <nav class="ml-10 flex h-full items-center space-x-2">
                        <Link
                            v-for="item in mainItems"
                            :key="item.title"
                            :href="item.href"
                            class="flex h-full items-center border-b-2 border-transparent px-3 text-sm font-medium text-foreground-subtle transition hover:text-foreground"
                            :class="activeItemStyles(item.href)"
                        >
                            <component
                                v-if="item.icon"
                                :is="Icon"
                                :name="item.icon"
                                class="mr-2 h-4 w-4"
                            />
                            {{ item.title }}
                        </Link>
                    </nav>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <Button
                        variant="ghost"
                        size="icon"
                        class="group h-9 w-9 cursor-pointer"
                    >
                        <Icon
                            name="lucide:search"
                            class="size-5 opacity-80 group-hover:opacity-100"
                        />
                    </Button>

                    <div class="hidden space-x-1 lg:flex">
                        <template v-for="item in rightItems" :key="item.title">
                            <Tooltip :delay-duration="0">
                                <template #trigger>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        as-child
                                        class="group h-9 w-9 cursor-pointer"
                                    >
                                        <component
                                            :is="isExternalLink(item) ? 'a' : Link"
                                            :href="isExternalLink(item) ? toUrl(item.href) : item.href"
                                            :target="isExternalLink(item) ? '_blank' : undefined"
                                            :rel="isExternalLink(item) ? 'noopener noreferrer' : undefined"
                                        >
                                            <span class="sr-only">{{ item.title }}</span>
                                            <Icon
                                                v-if="item.icon"
                                                :name="item.icon"
                                                class="size-5 opacity-80 group-hover:opacity-100"
                                            />
                                        </component>
                                    </Button>
                                </template>
                                {{ item.title }}
                            </Tooltip>
                        </template>
                    </div>

                    <DropdownMenu
                        align="end"
                        width="md"
                        trigger-variant="ghost"
                        trigger-size="icon"
                        trigger-class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                    >
                        <template #trigger-content>
                            <Avatar
                                size="sm"
                                :src="auth.user.avatar || ''"
                                :alt="auth.user.name"
                                :fallback="getInitials(auth.user?.name)"
                                class="size-8"
                            />
                        </template>
                        <UserMenuContent :user="auth.user" />
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div
            v-if="breadcrumbs.length > 1"
            class="flex w-full border-b border-border-muted"
        >
            <div
                class="mx-auto flex h-12 w-full items-center justify-start px-4 text-foreground-faint md:max-w-7xl"
            >
                <AppBreadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
