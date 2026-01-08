<script setup lang="ts">
import { computed } from 'vue';
import Breadcrumb from '@/components/ui/Breadcrumb.vue';
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const props = defineProps({
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
});

const items = computed(() =>
    (props.breadcrumbs as BreadcrumbItem[]).map((item, index) => ({
        label: item.title,
        href: item.href,
        current: index === props.breadcrumbs.length - 1,
        as: Link,
    })),
);
</script>

<template>
    <Breadcrumb :items="items" />
</template>
