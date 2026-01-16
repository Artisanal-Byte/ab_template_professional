<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import Icon from '@/components/Icon.vue';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', icon: 'lucide:sun', label: 'Light' },
    { value: 'dark', icon: 'lucide:moon', label: 'Dark' },
    { value: 'system', icon: 'lucide:monitor', label: 'System' },
] as const;
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-lg border border-border bg-secondary-subtle p-1"
    >
        <button
            v-for="{ value, icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-foreground-faint hover:bg-secondary-active hover:text-foreground',
            ]"
        >
            <Icon :name="icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
