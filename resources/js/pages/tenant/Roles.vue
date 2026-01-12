<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Checkbox from '@/components/ui/Checkbox.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import InputError from '@/components/InputError.vue';
import Badge from '@/components/ui/Badge.vue';
import { Form, Head } from '@inertiajs/vue3';
import RoleController from '@/actions/App/Http/Controllers/Tenant/RoleController';
import { dashboard } from '@/routes/tenant';
import type { BreadcrumbItem } from '@/types';

interface RoleItem {
    id: number;
    name: string;
    permission_count: number;
    permission_ids: number[];
}

interface PermissionItem {
    id: number;
    name: string;
}

defineProps<{
    roles: RoleItem[];
    permissions: PermissionItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tenant',
        href: dashboard().url,
    },
    {
        title: 'Roles',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Tenant Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
            <header>
                <h1 class="text-2xl font-semibold tracking-tight">Roles</h1>
                <p class="text-sm text-foreground-faint">
                    Create custom roles for this organization.
                </p>
            </header>

            <Card>
                <template #title>Create role</template>
                <template #description>Define a new tenant role.</template>
                <Form
                    v-bind="RoleController.store.form()"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-4 md:flex-row md:items-end"
                >
                    <div class="grid flex-1 gap-2">
                        <Label for="role-name">Role name</Label>
                        <Input id="role-name" name="name" placeholder="Quality Manager" />
                        <InputError :message="errors.name" />
                    </div>
                    <Button type="submit" :disabled="processing">Create role</Button>
                </Form>
            </Card>

            <Card>
                <template #title>Roles</template>
                <template #description>Existing tenant roles.</template>
                <div class="grid gap-3">
                    <div
                        v-for="role in roles"
                        :key="role.id"
                        class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-border p-4"
                    >
                        <div>
                            <div class="font-medium">{{ role.name }}</div>
                            <div class="text-xs text-foreground-faint">
                                {{ role.permission_count }} permissions
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Badge variant="secondary">Tenant</Badge>
                            <Dialog>
                                <template #trigger>
                                    <Button variant="outline" size="sm">Permissions</Button>
                                </template>
                                <template #title>Assign permissions</template>
                                <template #description>Set access for this role.</template>
                                <Form
                                    v-bind="RoleController.updatePermissions.form({ role: role.id })"
                                    v-slot="{ processing }"
                                    class="grid gap-3"
                                >
                                    <div class="grid gap-2">
                                        <p class="text-xs text-foreground-faint">
                                            Select the permissions this role should have.
                                        </p>
                                        <div class="grid gap-2">
                                            <label
                                                v-for="permission in permissions"
                                                :key="permission.id"
                                                class="flex items-center gap-2 text-sm"
                                            >
                                                <Checkbox
                                                    name="permissions[]"
                                                    :value="permission.id"
                                                    :default-value="role.permission_ids.includes(permission.id)"
                                                />
                                                <span>{{ permission.name }}</span>
                                            </label>
                                            <p v-if="permissions.length === 0" class="text-xs text-foreground-faint">
                                                No permissions available.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <Button type="submit" :disabled="processing">Save</Button>
                                    </div>
                                </Form>
                            </Dialog>
                            <Form v-bind="RoleController.destroy.form({ role: role.id })">
                                <Button variant="destructive" size="sm" type="submit">Delete</Button>
                            </Form>
                        </div>
                    </div>
                    <div v-if="roles.length === 0" class="rounded-lg border border-dashed border-border p-6 text-center text-sm text-foreground-faint">
                        No roles yet.
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
