<script setup lang="ts">
import { computed } from 'vue';
import Alert from '@/components/ui/Alert.vue';

const props = defineProps({
    errors: {
        type: Array,
        default: () => [],
    },
    title: {
        type: String,
        default: 'Something went wrong.',
    },
});

const uniqueErrors = computed(() => Array.from(new Set(props.errors)));
</script>

<template>
    <Alert variant="error">
        <template #title>
            <span class="flex items-center gap-2">
                <IconLucideAlertCircle class="size-4" />
                {{ title }}
            </span>
        </template>
        <template #description>
            <ul class="list-inside list-disc text-sm">
                <li v-for="(error, index) in uniqueErrors" :key="index">
                    {{ error }}
                </li>
            </ul>
        </template>
    </Alert>
</template>
