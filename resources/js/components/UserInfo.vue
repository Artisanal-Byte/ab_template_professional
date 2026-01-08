<script setup lang="ts">
import Avatar from '@/components/ui/Avatar.vue';
import { useInitials } from '@/composables/useInitials';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    showEmail: {
        type: Boolean,
        default: false,
    },
    compact: {
        type: Boolean,
        default: false,
    },
});

const { getInitials } = useInitials();
</script>

<template>
    <Avatar
        size="sm"
        :src="user.avatar || ''"
        :alt="user.name"
        :fallback="getInitials(user.name)"
        :class="props.compact ? '' : 'rounded-lg'"
    />

    <div
        v-if="!props.compact"
        class="grid flex-1 text-left text-sm leading-tight"
    >
        <span class="truncate font-medium">{{ props.user.name }}</span>
        <span
            v-if="props.showEmail"
            class="truncate text-xs text-foreground-faint"
        >
            {{ props.user.email }}
        </span>
    </div>
</template>

