<script setup lang="ts">
import Breadcrumb from '@/components/my-ui/Breadcrumb.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface BreadcrumbItemType {
    title: string;
    href?: string;
}

const props = defineProps<{
    breadcrumbs: BreadcrumbItemType[];
}>();

const items = computed(() =>
    props.breadcrumbs.map((item, index) => ({
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
