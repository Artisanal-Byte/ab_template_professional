<script setup lang="ts">
import { ref } from 'vue';
import Alert from '@/components/ui/Alert.vue';
import Avatar from '@/components/ui/Avatar.vue';
import Badge from '@/components/ui/Badge.vue';
import Breadcrumb from '@/components/ui/Breadcrumb.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import DropdownMenu from '@/components/ui/DropdownMenu.vue';
import Input from '@/components/ui/Input.vue';
import InputOTP from '@/components/ui/InputOTP.vue';
import Label from '@/components/ui/Label.vue';
import RichTextEditor from '@/components/ui/RichTextEditor.vue';
import Separator from '@/components/ui/Separator.vue';
import Skeleton from '@/components/ui/Skeleton.vue';
import Spinner from '@/components/ui/Spinner.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import Tooltip from '@/components/ui/Tooltip.vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { system as designSystem } from '@/routes/design';
import type { BreadcrumbItem } from '@/types';
import ButtonPlayground from '@/components/playgrounds/buttons/Playground.vue';
import BadgePlayground from '@/components/playgrounds/badges/Playground.vue';
import InputPlayground from '@/components/playgrounds/inputs/Playground.vue';
import DateTimePlayground from '@/components/playgrounds/date_time/Playground.vue';
import CheckboxPlayground from '@/components/playgrounds/checkbox_radio/CheckboxPlayground.vue';
import RadioPlayground from '@/components/playgrounds/checkbox_radio/RadioPlayground.vue';
import SelectPlayground from '@/components/playgrounds/selects/Playground.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Design System',
    href: designSystem().url,
  },
];

const tabs = ref([
  { id: 'foundations', label: 'Foundations' },
  { id: 'buttons', label: 'Buttons' },
  { id: 'inputs', label: 'Inputs' },
  { id: 'date-time', label: 'Date & Time' },
  { id: 'checkboxes', label: 'Checkboxes' },
  { id: 'radios', label: 'Radios' },
  { id: 'selects', label: 'Selects' },
  { id: 'feedback', label: 'Feedback' },
  { id: 'cards', label: 'Cards' },
  { id: 'navigation', label: 'Navigation' },
  { id: 'security', label: 'Security' },
  { id: 'overlays', label: 'Overlays' },
  { id: 'editors', label: 'Editors' },
  { id: 'badges', label: 'Badges' },
]);
const activeTab = ref(tabs.value[0].id);

// Base colors from the design system
const colors = ref([
  { name: 'Background', class: 'bg-background' },
  { name: 'Foreground', class: 'bg-foreground' },
  { name: 'Card', class: 'bg-card' },
  { name: 'Card Foreground', class: 'bg-card-foreground' },
  { name: 'Primary', class: 'bg-primary' },
  { name: 'Primary Foreground', class: 'bg-primary-foreground' },
  { name: 'Border', class: 'bg-border' },
]);

// Status colors
const statusColors = ref([
  { name: 'Neutral', class: 'bg-neutral' },
  { name: 'Secondary', class: 'bg-secondary' },
  { name: 'Accent', class: 'bg-accent' },
  { name: 'Info', class: 'bg-info' },
  { name: 'Success', class: 'bg-success' },
  { name: 'Warning', class: 'bg-warning' },
  { name: 'Error', class: 'bg-error' },
]);

const breadcrumbPrimary = [
  { label: 'Home', href: '/' },
  { label: 'Library', href: '/library' },
  { label: 'Data', current: true },
];

const breadcrumbSettings = [
  { label: 'Home', href: '/' },
  { ellipsis: true },
  { label: 'Settings', href: '/settings' },
  { label: 'Appearance', current: true },
];

const mentionItems = [
  { id: '1', label: 'Avery Grant' },
  { id: '2', label: 'Jules Park' },
  { id: '3', label: 'Morgan Lee' },
];

const editorContent = ref('<p>Draft your next quality update here.</p>');
</script>

<template>
  <Head title="Design System" />
  <AppLayout
    :breadcrumbs="breadcrumbs"
    :persist-nav-state="false"
    :initial-desktop-open="false"
  >
    <div class="flex flex-1 flex-col gap-6 p-4 lg:p-6">
      <header>
        <h1 class="text-4xl font-bold tracking-tight">
          Design System & Components
        </h1>
        <p class="mt-2 text-lg text-foreground-subtle">
          A showcase of the visual elements and components that form the application's UI.
        </p>
      </header>

      <div class="grid gap-8 lg:grid-cols-[220px_1fr]">
        <aside class="self-start lg:sticky lg:top-24">
          <div class="rounded-lg border border-border-subtle bg-card p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-foreground-faint">
              Sections
            </div>
            <nav class="mt-3 grid gap-2" aria-label="Sections">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                @click="activeTab = tab.id"
                :class="[
                  tab.id === activeTab
                    ? 'border border-primary bg-secondary-soft text-foreground'
                    : 'border border-transparent text-foreground-faint hover:border-border-subtle hover:bg-secondary-soft hover:text-foreground',
                  'w-full rounded-md px-3 py-2 text-left text-sm font-medium transition',
                ]"
              >
                {{ tab.label }}
              </button>
            </nav>
          </div>
        </aside>

        <main>
        <div v-if="activeTab === 'foundations'" class="grid gap-12">
          <section id="colors">
            <h2 class="mb-4 text-2xl font-semibold">Theme Colors</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7">
              <div v-for="color in colors" :key="color.name" class="flex flex-col items-center space-y-3">
                <div class="h-28 w-28 rounded-lg border border-border shadow-sm" :class="color.class"></div>
                <span class="text-sm font-medium text-foreground-subtle">{{ color.name }}</span>
              </div>
            </div>
          </section>
          <section id="status-colors">
            <h2 class="mb-4 text-2xl font-semibold">Status Colors</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7">
              <div v-for="color in statusColors" :key="color.name" class="flex flex-col items-center space-y-3">
                <div class="h-28 w-28 rounded-lg border border-border shadow-sm" :class="color.class"></div>
                <span class="text-sm font-medium text-foreground-subtle">{{ color.name }}</span>
              </div>
            </div>
          </section>
          <section id="type">
            <h2 class="mb-4 text-2xl font-semibold">Typography</h2>
            <div class="grid gap-2">
              <div class="text-4xl font-semibold tracking-tight">Display heading</div>
              <div class="text-2xl font-semibold">Section heading</div>
              <div class="text-base text-foreground-muted">
                Body text is built on the tokenized palette with light and dark theme support.
              </div>
              <div class="text-sm text-foreground-faint">Caption text for secondary details.</div>
            </div>
          </section>
          <section id="radii">
            <h2 class="mb-4 text-2xl font-semibold">Radii</h2>
            <div class="flex flex-wrap items-end gap-6">
              <div class="grid gap-2 text-sm text-foreground-subtle">
                <div class="h-12 w-24 rounded-sm bg-secondary-subtle"></div>
                <span>sm</span>
              </div>
              <div class="grid gap-2 text-sm text-foreground-subtle">
                <div class="h-12 w-24 rounded-md bg-secondary-subtle"></div>
                <span>md</span>
              </div>
              <div class="grid gap-2 text-sm text-foreground-subtle">
                <div class="h-12 w-24 rounded-lg bg-secondary-subtle"></div>
                <span>lg</span>
              </div>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'buttons'" class="grid gap-10 pb-6">
          <ButtonPlayground />
        </div>

        <div v-if="activeTab === 'inputs'" class="grid gap-10 pb-6">
          <InputPlayground />
        </div>

        <div v-if="activeTab === 'date-time'" class="grid gap-10 pb-6">
          <DateTimePlayground />
        </div>

        <div v-if="activeTab === 'checkboxes'" class="grid gap-10 pb-6">
          <CheckboxPlayground />
        </div>

        <div v-if="activeTab === 'radios'" class="grid gap-10 pb-6">
          <RadioPlayground />
        </div>

        <div v-if="activeTab === 'feedback'" class="grid gap-10 pb-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Alerts</h2>
            <div class="grid gap-4">
              <Alert>
                <template #title>Neutral notice</template>
                <template #description>
                  System updates are scheduled tonight at 10 PM.
                </template>
              </Alert>
              <Alert variant="info">
                <template #title>Info</template>
                <template #description>
                  New documents are ready for review.
                </template>
              </Alert>
              <Alert variant="success">
                <template #title>Success</template>
                <template #description>
                  Your changes were saved.
                </template>
              </Alert>
              <Alert variant="warning">
                <template #title>Warning</template>
                <template #description>
                  Some fields are missing required data.
                </template>
              </Alert>
              <Alert variant="error">
                <template #title>Error</template>
                <template #description>
                  We could not complete that action.
                </template>
              </Alert>
            </div>
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Loading</h2>
            <div class="flex flex-wrap items-center gap-6">
              <div class="flex items-center gap-2 text-sm text-foreground-subtle">
                <Spinner size="sm" />
                <span>Small</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-foreground-subtle">
                <Spinner />
                <span>Medium</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-foreground-subtle">
                <Spinner size="lg" />
                <span>Large</span>
              </div>
            </div>
            <div class="grid max-w-md gap-3">
              <Skeleton class="h-4 w-3/4" />
              <Skeleton class="h-4 w-full" />
              <Skeleton class="h-4 w-5/6" />
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'cards'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Cards</h2>
            <div class="grid gap-6 md:grid-cols-2">
              <Card>
                <template #title>Audit readiness</template>
                <template #description>
                  Stay ahead with templated checklists.
                </template>
                <div class="flex flex-wrap gap-2">
                  <Badge>Policies</Badge>
                  <Badge variant="primary">Workflows</Badge>
                  <Badge variant="success">Sign-off</Badge>
                </div>
                <template #footer>
                  <Button size="sm">Review now</Button>
                  <Button size="sm" variant="outline">Preview</Button>
                </template>
              </Card>
              <Card>
                <template #title>Team onboarding</template>
                <template #description>
                  Invite contributors and assign roles.
                </template>
                <div class="grid gap-2 text-sm text-foreground-subtle">
                  <div class="flex items-center justify-between">
                    <span>Policy templates</span>
                    <span>12</span>
                  </div>
                  <Separator />
                  <div class="flex items-center justify-between">
                    <span>Pending reviews</span>
                    <span>4</span>
                  </div>
                </div>
                <template #footer>
                  <div class="flex w-full justify-end">
                    <Button size="sm" variant="secondary">Invite</Button>
                  </div>
                </template>
              </Card>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'navigation'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Breadcrumbs</h2>
            <Breadcrumb :items="breadcrumbPrimary" />
            <Breadcrumb :items="breadcrumbSettings" />
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Avatars</h2>
            <div class="flex flex-wrap items-center gap-6">
              <div class="flex items-center gap-3 text-sm text-foreground-subtle">
                <Avatar size="sm" fallback="SM" />
                <span>Small</span>
              </div>
              <div class="flex items-center gap-3 text-sm text-foreground-subtle">
                <Avatar fallback="MD" />
                <span>Medium</span>
              </div>
              <div class="flex items-center gap-3 text-sm text-foreground-subtle">
                <Avatar size="lg">
                  <template #image>
                    <img
                      src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=120&h=120&fit=facearea&facepad=2"
                      alt="User avatar" class="h-full w-full object-cover" />
                  </template>
                </Avatar>
                <span>Large</span>
              </div>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'security'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">One-time passwords</h2>
            <div class="grid gap-4">
              <div class="grid gap-2">
                <Label for="otp-code">Verification code</Label>
                <InputOTP id="otp-code" :otp-length="6" />
              </div>
              <div class="grid gap-2">
                <Label for="otp-split">Split with separator</Label>
                <InputOTP id="otp-split" :otp-length="6" :separator-at="3" />
              </div>
              <div class="grid gap-2">
                <Label for="otp-short">4-digit OTP</Label>
                <InputOTP id="otp-short" :otp-length="4" />
              </div>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'overlays'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Tooltips</h2>
            <div class="flex flex-wrap items-center gap-4">
              <Tooltip>
                <template #trigger>
                  <Button variant="outline">Hover me</Button>
                </template>
                Tooltip text goes here.
              </Tooltip>
              <Tooltip side="right">
                <template #trigger>
                  <Button variant="ghost">Right tooltip</Button>
                </template>
                Positioned on the right.
              </Tooltip>
            </div>
          </section>

          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Dropdown menu</h2>
            <div class="flex flex-wrap items-start gap-6">
              <div class="w-full max-w-sm">
                <DropdownMenu
                  width="full"
                  trigger-label="Open menu"
                  trigger-variant="secondary"
                  trigger-size="md"
                >
                  <div class="grid gap-1">
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">
                      Profile
                    </button>
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">
                      Notifications
                    </button>
                    <div class="my-1 h-px bg-border"></div>
                    <button class="w-full rounded-md px-2 py-1 text-left text-error hover:bg-error-soft">
                      Sign out
                    </button>
                  </div>
                </DropdownMenu>
              </div>
              <div class="inline-flex">
                <DropdownMenu
                  width="xs"
                  trigger-label="Standard button"
                  trigger-variant="outline"
                  trigger-size="xs"
                >
                  <div class="grid gap-1">
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">
                      Quick action
                    </button>
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">
                      Export
                    </button>
                  </div>
                </DropdownMenu>
              </div>
            </div>

          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Dialog</h2>
            <Dialog>
              <template #trigger>
                <Button>Open dialog</Button>
              </template>
              <template #title>Invite collaborator</template>
              <template #description>
                Send an invite to a teammate and assign a role.
              </template>
              <div class="grid gap-3">
                <div class="grid gap-2">
                  <Label for="dialog-email">Email address</Label>
                  <Input id="dialog-email" type="email" placeholder="name@company.com" />
                </div>
                <div class="grid gap-2">
                  <Label for="dialog-role">Role</Label>
                  <Input id="dialog-role" placeholder="Reviewer" />
                </div>
              </div>
              <template #footer="{ close }">
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button variant="primary">Send invite</Button>
              </template>
            </Dialog>
          </section>
        </div>

        <div v-if="activeTab === 'selects'" class="grid gap-10 py-6">
          <SelectPlayground />
        </div>

        <div v-if="activeTab === 'editors'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Rich text editor</h2>
            <RichTextEditor
              v-model="editorContent"
              placeholder="Write update notes, type @ to mention..."
              :mention-items="mentionItems"
            />
            <div class="rounded-lg border border-border bg-card p-4 text-sm text-foreground-muted">
              <div class="mb-2 text-xs uppercase tracking-wide text-foreground-disabled">HTML output</div>
              <pre class="whitespace-pre-wrap break-words">{{ editorContent }}</pre>
            </div>
          </section>
        </div>
        <div v-if="activeTab === 'badges'" class="grid gap-10 py-6">
          <BadgePlayground />
        </div>
        </main>
      </div>
    </div>
  </AppLayout>
</template>
