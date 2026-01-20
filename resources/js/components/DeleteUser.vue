<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { Form } from '@inertiajs/vue3';
import { ref, useTemplateRef } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import FormError from '@/components/ui/FormError.vue';
import Button from '@/components/ui/Button.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
const passwordInput = useTemplateRef('passwordInput');
const isOpen = ref(false);
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall
            title="Delete account"
            description="Delete your account and all of its resources"
        />
        <div
            class="space-y-4 rounded-lg border border-error-border bg-error-soft p-4"
        >
            <div class="relative space-y-0.5 text-error">
                <p class="font-medium">Warning</p>
                <p class="text-sm">
                    Please proceed with caution, this cannot be undone.
                </p>
            </div>
            <Dialog v-model:open="isOpen">
                <template #trigger>
                    <Button variant="destructive" data-test="delete-user-button"
                        >Delete account</Button
                    >
                </template>
                <template #title>
                    Are you sure you want to delete your account?
                </template>
                <template #description>
                    Once your account is deleted, all of its resources and data
                    will also be permanently deleted. Please enter your
                    password to confirm you would like to permanently delete
                    your account.
                </template>
                <Form
                    v-bind="ProfileController.destroy.form()"
                    reset-on-success
                    @error="() => passwordInput?.$el?.focus()"
                    :options="{
                        preserveScroll: true,
                    }"
                    class="space-y-6"
                    v-slot="{ errors, processing, reset, clearErrors }"
                >
                    <div class="grid gap-2">
                        <Label for="password" class="sr-only">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            ref="passwordInput"
                            placeholder="Password"
                        />
                        <FormError :error="errors.password" />
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <Button
                            variant="secondary"
                            type="button"
                            @click="
                                () => {
                                    clearErrors();
                                    reset();
                                    isOpen = false;
                                }
                            "
                        >
                            Cancel
                        </Button>

                        <Button
                            type="submit"
                            variant="destructive"
                            :disabled="processing"
                            data-test="confirm-delete-user-button"
                        >
                            Delete account
                        </Button>
                    </div>
                </Form>
            </Dialog>
        </div>
    </div>
</template>


