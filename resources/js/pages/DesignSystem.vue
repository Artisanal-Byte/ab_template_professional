<script setup lang="ts">
import { ref } from 'vue';
import Alert from '@/components/my-ui/Alert.vue';
import Avatar from '@/components/my-ui/Avatar.vue';
import Badge from '@/components/my-ui/Badge.vue';
import Breadcrumb from '@/components/my-ui/Breadcrumb.vue';
import Button from '@/components/my-ui/Button.vue';
import Card from '@/components/my-ui/Card.vue';
import Checkbox from '@/components/my-ui/Checkbox.vue';
import Dialog from '@/components/my-ui/Dialog.vue';
import DropdownMenu from '@/components/my-ui/DropdownMenu.vue';
import Input from '@/components/my-ui/Input.vue';
import InputOTP from '@/components/my-ui/InputOTP.vue';
import Label from '@/components/my-ui/Label.vue';
import MultiSelect from '@/components/my-ui/MultiSelect.vue';
import RichTextEditor from '@/components/my-ui/RichTextEditor.vue';
import SearchAndSelect from '@/components/my-ui/SearchAndSelect.vue';
import Select from '@/components/my-ui/Select.vue';
import Separator from '@/components/my-ui/Separator.vue';
import Skeleton from '@/components/my-ui/Skeleton.vue';
import Spinner from '@/components/my-ui/Spinner.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import Tooltip from '@/components/my-ui/Tooltip.vue';

// This page does not use the standard AppLayout.vue because it is intended for public viewing
// without requiring authentication. It is a standalone page to showcase components.

const tabs = ref([
  { id: 'foundations', label: 'Foundations' },
  { id: 'buttons', label: 'Buttons' },
  { id: 'inputs', label: 'Inputs' },
  { id: 'feedback', label: 'Feedback' },
  { id: 'cards', label: 'Cards' },
  { id: 'navigation', label: 'Navigation' },
  { id: 'security', label: 'Security' },
  { id: 'overlays', label: 'Overlays' },
  { id: 'editors', label: 'Editors' },
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

const selectOptions = [
  { label: 'Clinical Operations', value: 'clinical', group: 'Departments' },
  { label: 'Quality Assurance', value: 'qa', group: 'Departments' },
  { label: 'Regulatory', value: 'regulatory', group: 'Departments' },
  { label: 'Change Control', value: 'change', group: 'Workflows' },
  { label: 'Deviation', value: 'deviation', group: 'Workflows' },
  { label: 'CAPA', value: 'capa', group: 'Workflows' },
];

const mentionItems = [
  { id: '1', label: 'Avery Grant' },
  { id: '2', label: 'Jules Park' },
  { id: '3', label: 'Morgan Lee' },
];

const selectedDepartment = ref('');
const selectedTeams = ref(['qa']);
const searchSelection = ref('');
const editorContent = ref('<p>Draft your next quality update here.</p>');
</script>

<template>
  <div class="min-h-screen bg-background font-sans text-foreground">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
      <header class="mb-8">
        <h1 class="text-4xl font-bold tracking-tight">
          Design System & Components
        </h1>
        <p class="mt-2 text-lg text-foreground/70">
          A showcase of the visual elements and components that form the application's UI.
        </p>
      </header>

      <div class="border-b border-border">
        <nav class="-mb-px flex flex-wrap gap-6" aria-label="Tabs">
          <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[
            tab.id === activeTab
              ? 'border-primary text-primary'
              : 'border-transparent text-foreground/60 hover:text-foreground hover:border-border',
            'whitespace-nowrap border-b-2 px-1 py-4 text-base font-medium transition-all duration-150 ease-in-out'
          ]">
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <main class="mt-8">
        <div v-if="activeTab === 'foundations'" class="grid gap-12">
          <section id="theme">
            <h2 class="mb-4 text-2xl font-semibold">Theme switcher</h2>
            <div class="flex flex-wrap items-center gap-4">
              <ThemeToggle />
              <p class="text-sm text-foreground/70">
                Toggles between light, dark, and system appearance.
              </p>
            </div>
          </section>
          <section id="colors">
            <h2 class="mb-4 text-2xl font-semibold">Theme Colors</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7">
              <div v-for="color in colors" :key="color.name" class="flex flex-col items-center space-y-3">
                <div class="h-28 w-28 rounded-lg border border-border shadow-sm" :class="color.class"></div>
                <span class="text-sm font-medium text-foreground/70">{{ color.name }}</span>
              </div>
            </div>
          </section>
          <section id="status-colors">
            <h2 class="mb-4 text-2xl font-semibold">Status Colors</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7">
              <div v-for="color in statusColors" :key="color.name" class="flex flex-col items-center space-y-3">
                <div class="h-28 w-28 rounded-lg border border-border shadow-sm" :class="color.class"></div>
                <span class="text-sm font-medium text-foreground/70">{{ color.name }}</span>
              </div>
            </div>
          </section>
          <section id="type">
            <h2 class="mb-4 text-2xl font-semibold">Typography</h2>
            <div class="grid gap-2">
              <div class="text-4xl font-semibold tracking-tight">Display heading</div>
              <div class="text-2xl font-semibold">Section heading</div>
              <div class="text-base text-foreground/80">
                Body text is built on the tokenized palette with light and dark theme support.
              </div>
              <div class="text-sm text-foreground/60">Caption text for secondary details.</div>
            </div>
          </section>
          <section id="radii">
            <h2 class="mb-4 text-2xl font-semibold">Radii</h2>
            <div class="flex flex-wrap items-end gap-6">
              <div class="grid gap-2 text-sm text-foreground/70">
                <div class="h-12 w-24 rounded-sm bg-secondary/40"></div>
                <span>sm</span>
              </div>
              <div class="grid gap-2 text-sm text-foreground/70">
                <div class="h-12 w-24 rounded-md bg-secondary/40"></div>
                <span>md</span>
              </div>
              <div class="grid gap-2 text-sm text-foreground/70">
                <div class="h-12 w-24 rounded-lg bg-secondary/40"></div>
                <span>lg</span>
              </div>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'buttons'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Variants</h2>
            <div class="flex flex-wrap gap-3">
              <Button>Primary</Button>
              <Button variant="secondary">Secondary</Button>
              <Button variant="outline">Outline</Button>
              <Button variant="ghost">Ghost</Button>
              <Button variant="success">Success</Button>
              <Button variant="info">Info</Button>
              <Button variant="warning">Warning</Button>
              <Button variant="destructive">Destructive</Button>
              <Button variant="link">Link</Button>
              <Button size="icon" aria-label="Icon button">+</Button>
            </div>
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Sizes</h2>
            <div class="flex flex-wrap items-center gap-3">
              <Button size="sm">Small</Button>
              <Button size="md">Medium</Button>
              <Button size="lg">Large</Button>
              <Button size="icon" aria-label="Icon button">+</Button>
            </div>
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Badges</h2>
            <div class="flex flex-wrap gap-3">
              <Badge>Neutral</Badge>
              <Badge variant="primary">Primary</Badge>
              <Badge variant="success">Success</Badge>
              <Badge variant="warning">Warning</Badge>
              <Badge variant="error">Error</Badge>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'inputs'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Text Inputs</h2>
            <div class="grid max-w-md gap-4">
              <div class="grid gap-2">
                <Label for="ds-email">Email address</Label>
                <Input id="ds-email" type="email" placeholder="email@example.com" />
              </div>
              <div class="grid gap-2">
                <Label for="ds-password">Password</Label>
                <Input id="ds-password" type="password" placeholder="********" />
              </div>
              <div class="grid gap-2">
                <Label for="ds-disabled">Disabled</Label>
                <Input id="ds-disabled" disabled placeholder="Disabled input" />
              </div>
            </div>
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Selects</h2>
            <div class="grid gap-4 md:grid-cols-2">
              <div class="grid gap-2">
                <Label>Standard select</Label>
                <Select
                  v-model="selectedDepartment"
                  :options="selectOptions"
                  placeholder="Choose a workflow"
                  width="full"
                />
              </div>
              <div class="grid gap-2">
                <Label>Multi select</Label>
                <MultiSelect
                  v-model="selectedTeams"
                  :options="selectOptions"
                  placeholder="Choose departments"
                  width="full"
                />
              </div>
            </div>
            <div class="grid gap-2">
              <Label>Search and select</Label>
              <SearchAndSelect
                v-model="searchSelection"
                :options="selectOptions"
                :allow-create="true"
                placeholder="Search workflows"
                width="full"
                @create="(value) => { searchSelection = value; }"
              />
            </div>
          </section>
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Checkboxes</h2>
            <div class="grid gap-3 text-sm">
              <Label for="ds-remember" class="flex items-center gap-3">
                <Checkbox id="ds-remember" />
                <span>Remember me</span>
              </Label>
              <Label for="ds-updates" class="flex items-center gap-3">
                <Checkbox id="ds-updates" />
                <span>Send product updates</span>
              </Label>
            </div>
          </section>
        </div>

        <div v-if="activeTab === 'feedback'" class="grid gap-10 py-6">
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
              <div class="flex items-center gap-2 text-sm text-foreground/70">
                <Spinner size="sm" />
                <span>Small</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-foreground/70">
                <Spinner />
                <span>Medium</span>
              </div>
              <div class="flex items-center gap-2 text-sm text-foreground/70">
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
                <div class="grid gap-2 text-sm text-foreground/70">
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
              <div class="flex items-center gap-3 text-sm text-foreground/70">
                <Avatar size="sm" fallback="SM" />
                <span>Small</span>
              </div>
              <div class="flex items-center gap-3 text-sm text-foreground/70">
                <Avatar fallback="MD" />
                <span>Medium</span>
              </div>
              <div class="flex items-center gap-3 text-sm text-foreground/70">
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
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary/50">
                      Profile
                    </button>
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary/50">
                      Notifications
                    </button>
                    <div class="my-1 h-px bg-border"></div>
                    <button class="w-full rounded-md px-2 py-1 text-left text-error hover:bg-error/10">
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
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary/50">
                      Quick action
                    </button>
                    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary/50">
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

        <div v-if="activeTab === 'editors'" class="grid gap-10 py-6">
          <section class="grid gap-4">
            <h2 class="text-2xl font-semibold">Rich text editor</h2>
            <RichTextEditor
              v-model="editorContent"
              placeholder="Write update notes, type @ to mention..."
              :mention-items="mentionItems"
            />
          </section>
        </div>
      </main>
    </div>
  </div>
</template>
