<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import Separator from '@/components/ui/Separator.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header class="flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Dashboard
                    </h1>
                    <p class="text-sm text-foreground-faint">
                        Snapshot of recent activity and upcoming work.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Badge variant="primary">Active</Badge>
                    <Badge>Starter</Badge>
                </div>
            </header>

            <section class="grid gap-4 md:grid-cols-3">
                <Card>
                    <template #title>Team inbox</template>
                    <template #description>Updates to review</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Project rhythm</template>
                    <template #description>Upcoming milestones</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Quality signals</template>
                    <template #description>System health</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
            </section>

            <section>
                <Card>
                    <template #title>Recent activity</template>
                    <template #description>
                        Highlights from your workspace.
                    </template>
                    <div class="grid gap-4 text-sm text-foreground-subtle">
                        <div class="flex items-center justify-between">
                            <span>Welcome message published</span>
                            <span>2 hours ago</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>Project plan refreshed</span>
                            <span>Today</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>Milestone review scheduled</span>
                            <span>Yesterday</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="relative min-h-[40vh] overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                            <PlaceholderPattern />
                        </div>
                    </div>
                </Card>
            </section>
        </div>
    </AppLayout>
</template>
