<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();

const options = [
    { value: 'light', label: 'Light', Icon: Sun },
    { value: 'dark', label: 'Dark', Icon: Moon },
    { value: 'system', label: 'System', Icon: Monitor },
] as const;
</script>

<template>
    <div class="inline-flex items-center gap-1 rounded-lg border border-border bg-secondary-subtle p-1">
        <button
            v-for="{ value, label, Icon } in options"
            :key="value"
            type="button"
            :aria-pressed="appearance === value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center gap-2 rounded-md px-3 py-1.5 text-sm transition-colors',
                appearance === value
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-foreground-faint hover:bg-secondary-active hover:text-foreground',
            ]"
        >
            <component :is="Icon" class="h-4 w-4" />
            <span>{{ label }}</span>
        </button>
    </div>
</template>

