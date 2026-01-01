<script setup lang="ts">
import { computed } from 'vue';
import Dialog from '@/components/my-ui/Dialog.vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    desktopWidth: {
        type: String,
        default: 'w-64',
    },
    collapsedWidth: {
        type: String,
        default: 'w-16',
    },
    mobileWidth: {
        type: String,
        default: 'w-[280px]',
    },
    desktopClass: {
        type: String,
        default: '',
    },
    mobileClass: {
        type: String,
        default: '',
    },
});

const desktopOpen = defineModel('desktopOpen', {
    type: Boolean,
    default: true,
});

const mobileOpen = defineModel('mobileOpen', {
    type: Boolean,
    default: false,
});

const desktopClasses = computed(() =>
    cn(
        'peer sticky top-0 hidden h-screen shrink-0 flex-col border-r border-border bg-card text-card-foreground transition-[width] duration-200 ease-in-out md:flex',
        desktopOpen.value ? props.desktopWidth : props.collapsedWidth,
        props.desktopClass,
    ),
);

const mobileContentClasses = computed(() =>
    cn(
        'left-0 top-0 h-full rounded-none border-r border-border bg-card text-card-foreground shadow-lg',
        props.mobileWidth,
        'p-4',
        props.mobileClass,
    ),
);
</script>

<template>
    <aside :class="desktopClasses" :data-collapsed="!desktopOpen">
        <div class="flex h-full flex-col">
            <slot :collapsed="!desktopOpen" />
        </div>
    </aside>

    <div class="md:hidden">
        <Dialog
            v-model:open="mobileOpen"
            :show-close="false"
            transition="slide-left"
            :content-class="mobileContentClasses"
        >
            <div class="flex h-full flex-col">
                <slot :collapsed="false" />
            </div>
        </Dialog>
    </div>
</template>
