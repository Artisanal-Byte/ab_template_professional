<script setup lang="ts">
import Badge from '@/components/my-ui/Badge.vue';
import Button from '@/components/my-ui/Button.vue';
import Card from '@/components/my-ui/Card.vue';
import Separator from '@/components/my-ui/Separator.vue';
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-background text-foreground">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col gap-12 px-6 py-10 lg:py-16">
            <header class="flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-sm font-semibold text-primary-foreground">
                        NQ
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-foreground/60">Neujin eQMS</p>
                        <p class="text-base font-semibold">Quality workspace</p>
                    </div>
                </div>
                <nav class="flex flex-wrap items-center gap-3">
                    <Button v-if="$page.props.auth.user" :as="Link" :href="dashboard()">
                        Dashboard
                    </Button>
                    <template v-else>
                        <Button :as="Link" :href="login()" variant="ghost">
                            Log in
                        </Button>
                        <Button v-if="canRegister" :as="Link" :href="register()">
                            Register
                        </Button>
                    </template>
                </nav>
            </header>

            <main class="grid items-center gap-10 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="grid gap-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <Badge variant="primary">QMS Platform</Badge>
                        <Badge>Validated workflows</Badge>
                        <Badge variant="success">Audit ready</Badge>
                    </div>
                    <h1 class="text-4xl font-semibold tracking-tight lg:text-5xl">
                        Quality systems you can trust, built to scale with your team.
                    </h1>
                    <p class="text-lg text-foreground/70">
                        Replace fragmented compliance tools with a focused workspace for SOPs, training,
                        and approvals. Designed for clarity, speed, and confident audits.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <Button :as="Link" :href="login()">Get started</Button>
                        <Button :as="Link" :href="register()" variant="outline">
                            Request access
                        </Button>
                    </div>
                </section>

                <section class="relative">
                    <div class="absolute -top-6 right-4 hidden h-24 w-24 rounded-full bg-accent/20 blur-3xl lg:block"></div>
                    <Card class="relative">
                        <template #title>Quick start checklist</template>
                        <template #description>
                            Everything you need to launch a compliant workspace.
                        </template>
                        <div class="grid gap-3 text-sm text-foreground/70">
                            <div class="flex items-center justify-between">
                                <span>Import your SOPs</span>
                                <span>10 min</span>
                            </div>
                            <Separator />
                            <div class="flex items-center justify-between">
                                <span>Assign training</span>
                                <span>15 min</span>
                            </div>
                            <Separator />
                            <div class="flex items-center justify-between">
                                <span>Launch approvals</span>
                                <span>5 min</span>
                            </div>
                        </div>
                        <template #footer>
                            <div class="flex w-full justify-end">
                                <Button size="sm" :as="Link" :href="dashboard()">Open workspace</Button>
                            </div>
                        </template>
                    </Card>
                </section>
            </main>
        </div>
    </div>
</template>
