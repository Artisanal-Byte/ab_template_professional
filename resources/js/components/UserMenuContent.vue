<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <div class="grid gap-2">
        <div class="px-2 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
        <div class="h-px bg-border"></div>
        <Link
            class="flex w-full items-center gap-2 rounded-md px-2 py-1 text-sm hover:bg-secondary/50"
            :href="edit()"
            prefetch
            as="button"
        >
            <Settings class="h-4 w-4" />
            Settings
        </Link>
        <div class="h-px bg-border"></div>
        <Link
            class="flex w-full items-center gap-2 rounded-md px-2 py-1 text-sm text-error hover:bg-error/10"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="h-4 w-4" />
            Log out
        </Link>
    </div>
</template>
