<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import FormError from '@/components/ui/FormError.vue';
import Badge from '@/components/ui/Badge.vue';
import { Form, Head } from '@inertiajs/vue3';
import TenantController from '@/actions/App/Http/Controllers/Admin/TenantController';
import { dashboard } from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

interface TenantOwner {
    id: number;
    name: string;
    email: string;
}

interface Tenant {
    id: string;
    name: string;
    slug: string;
    status: string;
    provisioned_at: string | null;
    owner: TenantOwner | null;
}

defineProps<{
    tenants: Tenant[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Organizations" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Organizations
                    </h1>
                    <p class="text-sm text-foreground-faint">
                        Create and manage tenant organizations.
                    </p>
                </div>
            </header>

            <Card>
                <template #title>Create organization</template>
                <template #description>Provision a new tenant and owner.</template>
                <Form
                    v-bind="TenantController.store.form()"
                    v-slot="{ errors, processing }"
                    class="grid gap-4 md:grid-cols-3"
                >
                    <div class="grid gap-2">
                        <Label for="tenant-name">Organization name</Label>
                        <Input id="tenant-name" name="name" placeholder="Neujin Bio Labs" />
                        <FormError :error="errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="tenant-slug">Slug</Label>
                        <Input id="tenant-slug" name="slug" placeholder="neujin_labs" />
                        <FormError :error="errors.slug" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="owner-name">Owner name</Label>
                        <Input id="owner-name" name="owner_name" placeholder="Owner name" />
                        <FormError :error="errors.owner_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="owner-email">Owner email</Label>
                        <Input id="owner-email" name="owner_email" type="email" placeholder="owner@org.com" />
                        <FormError :error="errors.owner_email" />
                    </div>
                    <div class="grid gap-2 md:col-span-2">
                        <Label for="owner-password">Owner password (new users)</Label>
                        <Input id="owner-password" name="owner_password" type="password" placeholder="••••••••" />
                        <FormError :error="errors.owner_password" />
                    </div>
                    <div class="flex items-center gap-2 md:col-span-3">
                        <Button type="submit" :disabled="processing">Create organization</Button>
                    </div>
                </Form>
            </Card>

            <Card>
                <template #title>Organizations</template>
                <template #description>Tenant provisioning status.</template>
                <div class="overflow-hidden rounded-lg border border-border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-secondary-soft text-left text-xs uppercase tracking-wide text-foreground-faint">
                            <tr>
                                <th class="px-4 py-3">Organization</th>
                                <th class="px-4 py-3">Owner</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Provisioned</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="tenant in tenants" :key="tenant.id">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ tenant.name }}</div>
                                    <div class="text-xs text-foreground-faint">{{ tenant.slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-foreground-subtle">
                                    <div>{{ tenant.owner?.name ?? 'Unassigned' }}</div>
                                    <div class="text-xs">{{ tenant.owner?.email ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="tenant.status === 'active' ? 'success' : 'warning'">
                                        {{ tenant.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-foreground-subtle">
                                    {{ tenant.provisioned_at ?? 'Pending' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <Dialog>
                                            <template #trigger>
                                                <Button variant="outline" size="sm">Edit</Button>
                                            </template>
                                            <template #title>Edit organization</template>
                                            <template #description>Update tenant details.</template>
                                            <Form
                                                v-bind="TenantController.update.form({ tenant: tenant.id })"
                                                v-slot="{ errors, processing }"
                                                class="grid gap-3"
                                            >
                                                <div class="grid gap-2">
                                                    <Label :for="`tenant-name-${tenant.id}`">Organization name</Label>
                                                    <Input :id="`tenant-name-${tenant.id}`" name="name" :default-value="tenant.name" />
                                                    <FormError :error="errors.name" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`tenant-slug-${tenant.id}`">Slug</Label>
                                                    <Input :id="`tenant-slug-${tenant.id}`" name="slug" :default-value="tenant.slug" />
                                                    <FormError :error="errors.slug" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`owner-email-${tenant.id}`">Owner email</Label>
                                                    <Input
                                                        :id="`owner-email-${tenant.id}`"
                                                        name="owner_email"
                                                        type="email"
                                                        :default-value="tenant.owner?.email ?? ''"
                                                    />
                                                    <FormError :error="errors.owner_email" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`owner-password-${tenant.id}`">New owner password</Label>
                                                    <Input :id="`owner-password-${tenant.id}`" name="owner_password" type="password" />
                                                    <FormError :error="errors.owner_password" />
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <Button type="submit" :disabled="processing">
                                                        Save
                                                    </Button>
                                                </div>
                                            </Form>
                                        </Dialog>
                                        <Form
                                            v-bind="TenantController.updateStatus.form({ tenant: tenant.id })"
                                            class="inline-flex"
                                        >
                                            <input
                                                type="hidden"
                                                name="status"
                                                :value="tenant.status === 'active' ? 'suspended' : 'active'"
                                            />
                                            <Button variant="outline" size="sm" type="submit">
                                                {{ tenant.status === 'active' ? 'Suspend' : 'Activate' }}
                                            </Button>
                                        </Form>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="tenants.length === 0">
                                <td class="px-4 py-6 text-center text-foreground-faint" colspan="5">
                                    No organizations yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>

