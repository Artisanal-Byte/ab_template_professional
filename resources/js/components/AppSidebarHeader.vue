<script setup lang="ts">
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Button from '@/components/ui/Button.vue';
import { useAppearance } from '@/composables/useAppearance';

type BreadcrumbItemType = {
    title: string;
    href?: string;
};

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['toggle-desktop', 'toggle-mobile']);

const { appearance, updateAppearance } = useAppearance();

const toggleTheme = () => {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
};

const breadcrumbItems = computed(
    () => props.breadcrumbs as BreadcrumbItemType[],
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between border-b border-border px-6 md:px-4"
    >
        <div class="flex items-center gap-2">
            <Button
                variant="ghost"
                size="icon"
                class="-ml-1 md:hidden"
                aria-label="Open navigation"
                @click="emit('toggle-mobile')"
            >
                <IconLucideMenu class="h-4 w-4" />
            </Button>
            <Button
                variant="ghost"
                size="icon"
                class="-ml-1 hidden md:inline-flex"
                aria-label="Toggle navigation"
                @click="emit('toggle-desktop')"
            >
                <IconLucideMenu class="h-4 w-4" />
            </Button>
            <template v-if="breadcrumbItems.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbItems" />
            </template>
        </div>
        <Button
            variant="ghost"
            size="icon"
            class="h-9 w-9"
            aria-label="Toggle theme"
            @click="toggleTheme"
        >
            <IconLucideSun v-if="appearance === 'dark'" class="h-4 w-4" />
            <IconLucideMoon v-else class="h-4 w-4" />
        </Button>
    </header>
</template>
