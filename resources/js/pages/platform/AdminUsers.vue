<script setup lang="ts">
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Select from '@/components/ui/Select.vue';
import InputError from '@/components/InputError.vue';
import Badge from '@/components/ui/Badge.vue';
import { Form, Head } from '@inertiajs/vue3';
import AdminUserController from '@/actions/App/Http/Controllers/Platform/AdminUserController';
import { dashboard } from '@/routes/platform';
import type { BreadcrumbItem } from '@/types';

interface AdminUser {
    id: number;
    name: string;
    email: string;
    status: string;
}

const props = defineProps<{
    admins: AdminUser[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Platform',
        href: dashboard().url,
    },
];

const adminStatusOptions = [
    { label: 'Active', value: 'active' },
    { label: 'Locked', value: 'locked' },
    { label: 'Disabled', value: 'disabled' },
];

const adminStatusSelections = ref<Record<number, string>>({});

watch(
    () => props.admins,
    (admins) => {
        adminStatusSelections.value = admins.reduce<Record<number, string>>((acc, admin) => {
            acc[admin.id] = admin.status;
            return acc;
        }, {});
    },
    { immediate: true },
);
</script>

<template>
    <Head title="Admin Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Admin Users
                    </h1>
                    <p class="text-sm text-foreground-faint">
                        Create and manage platform administrators.
                    </p>
                </div>
            </header>

            <Card>
                <template #title>Create Admin</template>
                <template #description>Invite a new admin user.</template>
                <Form
                    v-bind="AdminUserController.store.form()"
                    v-slot="{ errors, processing }"
                    class="grid gap-4 md:grid-cols-3"
                >
                    <div class="grid gap-2">
                        <Label for="admin-name">Name</Label>
                        <Input id="admin-name" name="name" placeholder="Full name" />
                        <InputError :message="errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="admin-email">Email</Label>
                        <Input id="admin-email" name="email" type="email" placeholder="email@company.com" />
                        <InputError :message="errors.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="admin-password">Password</Label>
                        <Input id="admin-password" name="password" type="password" placeholder="••••••••" />
                        <InputError :message="errors.password" />
                    </div>
                    <div class="flex items-center gap-2 md:col-span-3">
                        <Button type="submit" :disabled="processing">Create admin</Button>
                    </div>
                </Form>
            </Card>

            <Card>
                <template #title>Admins</template>
                <template #description>Active admin accounts.</template>
                <div class="overflow-hidden rounded-lg border border-border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-secondary-soft text-left text-xs uppercase tracking-wide text-foreground-faint">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="admin in admins" :key="admin.id">
                                <td class="px-4 py-3 font-medium">
                                    {{ admin.name }}
                                </td>
                                <td class="px-4 py-3 text-foreground-subtle">
                                    {{ admin.email }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="admin.status === 'active' ? 'success' : 'warning'">
                                        {{ admin.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <Dialog>
                                            <template #trigger>
                                                <Button variant="outline" size="sm">Edit</Button>
                                            </template>
                                            <template #title>Edit admin</template>
                                            <template #description>Update admin details.</template>
                                            <Form
                                                v-bind="AdminUserController.update.form({ user: admin.id })"
                                                v-slot="{ errors, processing }"
                                                class="grid gap-3"
                                            >
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-name-${admin.id}`">Name</Label>
                                                    <Input :id="`edit-name-${admin.id}`" name="name" :default-value="admin.name" />
                                                    <InputError :message="errors.name" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-email-${admin.id}`">Email</Label>
                                                    <Input :id="`edit-email-${admin.id}`" name="email" type="email" :default-value="admin.email" />
                                                    <InputError :message="errors.email" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-status-${admin.id}`">Status</Label>
                                                    <Select
                                                        :id="`edit-status-${admin.id}`"
                                                        v-model="adminStatusSelections[admin.id]"
                                                        name="status"
                                                        :options="adminStatusOptions"
                                                        placeholder="Select status"
                                                        width="full"
                                                        :error="errors.status"
                                                    />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-password-${admin.id}`">New password</Label>
                                                    <Input :id="`edit-password-${admin.id}`" name="password" type="password" />
                                                    <InputError :message="errors.password" />
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <Button type="submit" :disabled="processing">
                                                        Save
                                                    </Button>
                                                </div>
                                            </Form>
                                        </Dialog>
                                        <Form
                                            v-bind="AdminUserController.destroy.form({ user: admin.id })"
                                            class="inline-flex"
                                        >
                                            <Button variant="destructive" size="sm" type="submit">
                                                Delete
                                            </Button>
                                        </Form>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="admins.length === 0">
                                <td class="px-4 py-6 text-center text-foreground-faint" colspan="4">
                                    No admins yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
