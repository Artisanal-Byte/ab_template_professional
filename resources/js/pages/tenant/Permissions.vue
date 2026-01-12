<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import InputError from '@/components/InputError.vue';
import Badge from '@/components/ui/Badge.vue';
import { Form, Head } from '@inertiajs/vue3';
import PermissionController from '@/actions/App/Http/Controllers/Tenant/PermissionController';
import { dashboard } from '@/routes/tenant';
import type { BreadcrumbItem } from '@/types';

interface PermissionItem {
    id: number;
    name: string;
}

defineProps<{
    permissions: PermissionItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tenant',
        href: dashboard().url,
    },
    {
        title: 'Permissions',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Tenant Permissions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header>
                <h1 class="text-2xl font-semibold tracking-tight">Permissions</h1>
                <p class="text-sm text-foreground-faint">
                    Define tenant-specific permissions for roles.
                </p>
            </header>

            <Card>
                <template #title>Create permission</template>
                <template #description>Add a new permission.</template>
                <Form
                    v-bind="PermissionController.store.form()"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-4 md:flex-row md:items-end"
                >
                    <div class="grid flex-1 gap-2">
                        <Label for="permission-name">Permission name</Label>
                        <Input id="permission-name" name="name" placeholder="documents.publish" />
                        <InputError :message="errors.name" />
                    </div>
                    <Button type="submit" :disabled="processing">Create permission</Button>
                </Form>
            </Card>

            <Card>
                <template #title>Permissions</template>
                <template #description>Existing tenant permissions.</template>
                <div class="grid gap-3">
                    <div
                        v-for="permission in permissions"
                        :key="permission.id"
                        class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-border p-4"
                    >
                        <div class="font-medium">{{ permission.name }}</div>
                        <div class="flex items-center gap-2">
                            <Badge variant="secondary">Tenant</Badge>
                            <Form v-bind="PermissionController.destroy.form({ permission: permission.id })">
                                <Button variant="destructive" size="sm" type="submit">Delete</Button>
                            </Form>
                        </div>
                    </div>
                    <div v-if="permissions.length === 0" class="rounded-lg border border-dashed border-border p-6 text-center text-sm text-foreground-faint">
                        No permissions yet.
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
