<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});
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
        <NavMain :items="mainNavItems" :collapsed="props.collapsed" />
    </div>

    <div class="mt-auto space-y-4 border-t border-border py-4">
        <NavFooter :items="footerNavItems" :collapsed="props.collapsed" />
        <NavUser :collapsed="props.collapsed" />
    </div>
</template>
