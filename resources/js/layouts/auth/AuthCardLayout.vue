<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import Card from '@/components/ui/Card.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-secondary-soft p-6 md:p-10"
    >
        <div class="flex w-full max-w-md flex-col gap-6">
            <Link
                :href="home()"
                class="flex items-center gap-2 self-center font-medium"
            >
                <AppLogo
                    :show-text="false"
                    size="lg"
                    container-class="bg-transparent text-foreground"
                />
            </Link>

            <div class="flex flex-col gap-6">
                <Card
                    class="rounded-xl"
                    header-class="px-6 pt-6 pb-2 text-center"
                    content-class="px-6 pb-6 pt-4"
                >
                    <template #title>{{ title }}</template>
                    <template #description>{{ description }}</template>
                    <slot />
                </Card>
            </div>
        </div>
    </div>
</template>

