<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import Separator from '@/components/ui/Separator.vue';
import { dashboard as tenantDashboard } from '@/routes/tenant';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: tenantDashboard().url,
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
                        Snapshot of compliance activity and recent work.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Badge variant="primary">Active</Badge>
                    <Badge>QMS Core</Badge>
                </div>
            </header>

            <section class="grid gap-4 md:grid-cols-3">
                <Card>
                    <template #title>Pending approvals</template>
                    <template #description>Awaiting sign-off</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Training status</template>
                    <template #description>Required sessions</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Audit readiness</template>
                    <template #description>Key checkpoints</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
            </section>

            <section>
                <Card>
                    <template #title>Recent activity</template>
                    <template #description>
                        Latest updates across policies and workflows.
                    </template>
                    <div class="grid gap-4 text-sm text-foreground-subtle">
                        <div class="flex items-center justify-between">
                            <span>SOP-14 updated</span>
                            <span>2 hours ago</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>Training cycle published</span>
                            <span>Today</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>CAPA review assigned</span>
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
