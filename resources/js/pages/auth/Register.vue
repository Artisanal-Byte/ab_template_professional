<script setup lang="ts">
import FormError from '@/components/ui/FormError.vue';
import TextLink from '@/components/TextLink.vue';
import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Spinner from '@/components/ui/Spinner.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        title="Create your account"
        description="Share a few details to get started"
    >
        <Head title="Register" />

        <Form
            v-bind="store()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Full name</Label>
                    <Input
                        id="name"
                        name="name"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        placeholder="Jane Doe"
                    />
                    <FormError :error="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <FormError :error="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        placeholder="Create a password"
                    />
                    <FormError :error="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        placeholder="Repeat your password"
                    />
                    <FormError :error="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    :tabindex="5"
                    :disabled="processing"
                    data-test="register-button"
                >
                    <Spinner v-if="processing" />
                    Create account
                </Button>
            </div>

            <p class="text-center text-sm text-foreground-subtle">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="text-sm"
                    :tabindex="6"
                >
                    Log in
                </TextLink>
            </p>
        </Form>
    </AuthBase>
</template>
