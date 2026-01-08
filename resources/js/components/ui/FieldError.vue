<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    error: {
        type: [String, Array, Object],
        default: '',
    },
});

const isList = computed(
    () => Array.isArray(props.error) || (props.error && typeof props.error === 'object'),
);

const flattenErrors = (value: unknown): string[] => {
    if (!value) {
        return [];
    }
    if (typeof value === 'string') {
        return [value];
    }
    if (Array.isArray(value)) {
        return value.flatMap(flattenErrors).filter(Boolean);
    }
    if (typeof value === 'object') {
        return Object.values(value as Record<string, unknown>)
            .flatMap(flattenErrors)
            .filter(Boolean);
    }
    return [];
};

const messages = computed(() => flattenErrors(props.error));
</script>

<template>
    <p v-if="messages.length && !isList" class="text-sm text-error">
        {{ messages[0] }}
    </p>
    <ul v-else-if="messages.length" class="list-inside list-disc text-sm text-error">
        <li v-for="(error, index) in messages" :key="index">
            {{ error }}
        </li>
    </ul>
</template>
