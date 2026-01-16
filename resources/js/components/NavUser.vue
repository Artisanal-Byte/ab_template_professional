<script setup lang="ts">
import DropdownMenu from '@/components/ui/DropdownMenu.vue';
import UserInfo from '@/components/UserInfo.vue';
import { usePage } from '@inertiajs/vue3';
import UserMenuContent from './UserMenuContent.vue';

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <div v-if="user" class="px-2">
        <DropdownMenu
            width="full"
            :menu-width="props.collapsed ? 'lg' : ''"
            :align="props.collapsed ? 'start' : 'end'"
            :side="props.collapsed ? 'right' : 'top'"
            trigger-variant="ghost"
            trigger-size="md"
            :trigger-class="props.collapsed ? 'justify-center px-2 py-2' : 'justify-start px-3 py-2'"
            trigger-test-id="sidebar-menu-button"
        >
            <template #trigger-content>
                <div class="flex w-full items-center gap-2">
                    <UserInfo :user="user" :compact="props.collapsed" />
                    <IconLucideChevronsUpDown
                        v-if="!props.collapsed"
                        class="ml-auto h-4 w-4 text-foreground-faint"
                    />
                </div>
            </template>
            <UserMenuContent :user="user" />
        </DropdownMenu>
    </div>
</template>
