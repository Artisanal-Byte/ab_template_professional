<script setup lang="ts">
import Alert from '@/components/my-ui/Alert.vue';
import { AlertCircle } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    errors: string[];
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Something went wrong.',
});

const uniqueErrors = computed(() => Array.from(new Set(props.errors)));
</script>

<template>
    <Alert variant="error">
        <template #title>
            <span class="flex items-center gap-2">
                <AlertCircle class="size-4" />
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
