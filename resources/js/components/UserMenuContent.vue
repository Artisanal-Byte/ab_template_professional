<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import Icon from '@/components/Icon.vue';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import { Link, router } from '@inertiajs/vue3';

const handleLogout = () => {
    router.flushAll();
};

defineProps({
    user: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <div class="grid gap-2">
        <div class="px-2 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
        <div class="h-px bg-border"></div>
        <Link
            class="flex w-full items-center gap-2 rounded-md px-2 py-1 text-sm hover:bg-secondary-hover"
            :href="edit()"
            prefetch
            as="button"
        >
            <Icon name="lucide:settings" class="h-4 w-4" />
            Settings
        </Link>
        <div class="h-px bg-border"></div>
        <Link
            class="flex w-full items-center gap-2 rounded-md px-2 py-1 text-sm text-error hover:bg-error-soft"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <Icon name="lucide:log-out" class="h-4 w-4" />
            Log out
        </Link>
    </div>
</template>
