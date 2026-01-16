<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import Separator from '@/components/ui/Separator.vue';
import { dashboard } from '@/routes/tenant';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tenant Dashboard',
        href: dashboard().url,
    },
];

const page = usePage();
const currentTenant = page.props.currentTenant as { name?: string } | null;
</script>

<template>
    <Head title="Tenant Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header class="flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ currentTenant?.name ?? 'Tenant Dashboard' }}
                    </h1>
                    <p class="text-sm text-foreground-faint">
                        Quality management overview for this organization.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Badge variant="primary">Active</Badge>
                    <Badge>Tenant</Badge>
                </div>
            </header>

            <section class="grid gap-4 md:grid-cols-3">
                <Card>
                    <template #title>Documents</template>
                    <template #description>Drafts and approvals</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Workflows</template>
                    <template #description>In-flight reviews</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
                <Card>
                    <template #title>Training</template>
                    <template #description>Assignments due</template>
                    <div class="relative aspect-video overflow-hidden rounded-lg border border-border-subtle bg-secondary-soft">
                        <PlaceholderPattern />
                    </div>
                </Card>
            </section>

            <section>
                <Card>
                    <template #title>Recent activity</template>
                    <template #description>Latest tenant updates.</template>
                    <div class="grid gap-4 text-sm text-foreground-subtle">
                        <div class="flex items-center justify-between">
                            <span>Policy drafted</span>
                            <span>2 hours ago</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>Training assigned</span>
                            <span>Today</span>
                        </div>
                        <Separator class="bg-border-subtle" />
                        <div class="flex items-center justify-between">
                            <span>Workflow completed</span>
                            <span>Yesterday</span>
                        </div>
                    </div>
                </Card>
            </section>
        </div>
    </AppLayout>
</template>
