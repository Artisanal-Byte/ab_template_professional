<script setup lang="ts">
defineProps({
    message: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-error">
            {{ message }}
        </p>
    </div>
</template>
