<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Select from '@/components/ui/Select.vue';
import InputError from '@/components/InputError.vue';
import Badge from '@/components/ui/Badge.vue';
import { Form, Head, usePage } from '@inertiajs/vue3';
import StaffController from '@/actions/App/Http/Controllers/Tenant/StaffController';
import { dashboard } from '@/routes/tenant';
import type { BreadcrumbItem } from '@/types';

interface StaffMember {
    id: number;
    user_id: number;
    name: string;
    email: string;
    membership_role: string;
    status: string;
    roles: string[];
}

interface RoleOption {
    id: number;
    name: string;
}

const props = defineProps<{
    staff: StaffMember[];
    roles: RoleOption[];
}>();

const page = usePage();
const currentUserId = page.props.auth?.user?.id ?? null;
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tenant',
        href: dashboard().url,
    },
    {
        title: 'Staff',
        href: dashboard().url,
    },
];


const roleOptions = props.roles.map((role) => ({
    label: role.name,
    value: String(role.id),
}));
</script>

<template>
    <Head title="Staff" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header>
                <h1 class="text-2xl font-semibold tracking-tight">Staff</h1>
                <p class="text-sm text-foreground-faint">
                    Manage tenant staff accounts and roles.
                </p>
            </header>

            <Card>
                <template #title>Add staff member</template>
                <template #description>Create a new staff account or link an existing user.</template>
                <Form
                    v-bind="StaffController.store.form()"
                    v-slot="{ errors, processing }"
                    class="grid gap-4 md:grid-cols-2"
                >
                    <div class="grid gap-2">
                        <Label for="staff-name">Name</Label>
                        <Input id="staff-name" name="name" placeholder="Full name" />
                        <InputError :message="errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="staff-email">Email</Label>
                        <Input id="staff-email" name="email" type="email" placeholder="user@tenant.com" />
                        <InputError :message="errors.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="staff-password">Password (new users)</Label>
                        <Input id="staff-password" name="password" type="password" placeholder="••••••••" />
                        <InputError :message="errors.password" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="membership-role">Membership role</Label>
                        <Input id="membership-role" name="membership_role" placeholder="owner or employee" />
                        <InputError :message="errors.membership_role" />
                    </div>
                    <div class="flex items-center gap-2 md:col-span-2">
                        <Button type="submit" :disabled="processing">Add staff</Button>
                    </div>
                </Form>
            </Card>

            <Card>
                <template #title>Staff list</template>
                <template #description>Active staff in this tenant.</template>
                <div class="overflow-hidden rounded-lg border border-border">
                    <table class="min-w-full text-sm">
                        <thead class="bg-secondary-soft text-left text-xs uppercase tracking-wide text-foreground-faint">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Membership</th>
                                <th class="px-4 py-3">Roles</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="member in staff" :key="member.id">
                                <td class="px-4 py-3 font-medium">
                                    {{ member.name }}
                                </td>
                                <td class="px-4 py-3 text-foreground-subtle">
                                    {{ member.email }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="member.membership_role === 'owner' ? 'primary' : 'secondary'">
                                        {{ member.membership_role }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-foreground-subtle">
                                    {{ member.roles?.length ? member.roles.join(', ') : 'None' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <Dialog v-if="member.user_id !== currentUserId">
                                            <template #trigger>
                                                <Button variant="outline" size="sm">Edit</Button>
                                            </template>
                                            <template #title>Edit staff</template>
                                            <template #description>Update role or status.</template>
                                            <Form
                                                v-bind="StaffController.update.form({ tenantUser: member.id })"
                                                v-slot="{ errors, processing }"
                                                class="grid gap-3"
                                            >
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-role-${member.id}`">Membership role</Label>
                                                    <Input :id="`edit-role-${member.id}`" name="membership_role" :default-value="member.membership_role" />
                                                    <InputError :message="errors.membership_role" />
                                                </div>
                                                <div class="grid gap-2">
                                                    <Label :for="`edit-status-${member.id}`">Status</Label>
                                                    <Input :id="`edit-status-${member.id}`" name="status" :default-value="member.status" />
                                                    <InputError :message="errors.status" />
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <Button type="submit" :disabled="processing">Save</Button>
                                                </div>
                                            </Form>
                                        </Dialog>
                                        <Form
                                            v-if="member.user_id !== currentUserId"
                                            v-bind="StaffController.destroy.form({ tenantUser: member.id })"
                                            class="inline-flex"
                                        >
                                            <Button variant="destructive" size="sm" type="submit">Remove</Button>
                                        </Form>
                                    </div>
                                    <Form
                                        v-if="roleOptions.length"
                                        v-bind="StaffController.assignRole.form()"
                                        class="mt-3 grid gap-2"
                                    >
                                        <input type="hidden" name="user_id" :value="member.user_id" />
                                        <Label class="text-xs text-foreground-faint">Assign role</Label>
                                        <Select
                                            name="role_id"
                                            :options="roleOptions"
                                            placeholder="Choose role"
                                            width="full"
                                        />
                                        <Button type="submit" size="sm" variant="secondary">
                                            Apply
                                        </Button>
                                    </Form>
                                </td>
                            </tr>
                            <tr v-if="staff.length === 0">
                                <td class="px-4 py-6 text-center text-foreground-faint" colspan="5">
                                    No staff members yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
