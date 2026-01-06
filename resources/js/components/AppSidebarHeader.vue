<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Button from '@/components/my-ui/Button.vue';
import { useAppearance } from '@/composables/useAppearance';
import { Menu, Moon, Sun } from 'lucide-vue-next';

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
                <Menu class="h-4 w-4" />
            </Button>
            <Button
                variant="ghost"
                size="icon"
                class="-ml-1 hidden md:inline-flex"
                aria-label="Toggle navigation"
                @click="emit('toggle-desktop')"
            >
                <Menu class="h-4 w-4" />
            </Button>
            <template v-if="props.breadcrumbs && props.breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
            </template>
        </div>
        <Button
            variant="ghost"
            size="icon"
            class="h-9 w-9"
            aria-label="Toggle theme"
            @click="toggleTheme"
        >
            <Sun v-if="appearance === 'dark'" class="h-4 w-4" />
            <Moon v-else class="h-4 w-4" />
        </Button>
    </header>
</template>
