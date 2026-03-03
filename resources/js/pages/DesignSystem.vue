<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { system as designSystem } from '@/routes/design';
import type { BreadcrumbItem } from '@/types';
import ButtonPlayground from '@/components/playgrounds/buttons/Playground.vue';
import BadgePlayground from '@/components/playgrounds/badges/Playground.vue';
import IconPlayground from '@/components/playgrounds/icons/Playground.vue';
import InputPlayground from '@/components/playgrounds/inputs/Playground.vue';
import CurrencyInputPlayground from '@/components/playgrounds/currency/Playground.vue';
import DateTimePlayground from '@/components/playgrounds/date_time/Playground.vue';
import CheckboxPlayground from '@/components/playgrounds/checkbox_radio/CheckboxPlayground.vue';
import RadioPlayground from '@/components/playgrounds/checkbox_radio/RadioPlayground.vue';
import SelectPlayground from '@/components/playgrounds/selects/Playground.vue';
import AlertPlayground from '@/components/playgrounds/alerts/Playground.vue';
import ErrorPlayground from '@/components/playgrounds/errors/Playground.vue';
import LoadingPlayground from '@/components/playgrounds/loading/Playground.vue';
import CardsPlayground from '@/components/playgrounds/cards/Playground.vue';
import BreadcrumbPlayground from '@/components/playgrounds/breadcrumbs/Playground.vue';
import AvatarPlayground from '@/components/playgrounds/avatars/Playground.vue';
import OtpPlayground from '@/components/playgrounds/otp/Playground.vue';
import TooltipPlayground from '@/components/playgrounds/tooltips/Playground.vue';
import DropdownPlayground from '@/components/playgrounds/dropdowns/Playground.vue';
import ModalPlayground from '@/components/playgrounds/modals/Playground.vue';
import RichTextEditorPlayground from '@/components/playgrounds/rich_text/Playground.vue';

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
  { id: 'currency-input', label: 'Currency Input' },
  { id: 'date-time', label: 'Date & Time' },
  { id: 'checkboxes', label: 'Checkboxes' },
  { id: 'radios', label: 'Radios' },
  { id: 'selects', label: 'Selects' },
  { id: 'badges', label: 'Badges' },
  { id: 'icons', label: 'Icons' },
  { id: 'alerts', label: 'Alerts' },
  { id: 'errors', label: 'Errors' },
  { id: 'loading', label: 'Loading' },
  { id: 'cards', label: 'Cards' },
  { id: 'breadcrumbs', label: 'Breadcrumbs' },
  { id: 'avatars', label: 'Avatars' },
  { id: 'otp', label: 'OTP' },
  { id: 'tooltips', label: 'Tooltips' },
  { id: 'dropdowns', label: 'Dropdowns' },
  { id: 'modals', label: 'Modals' },
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

        <div v-if="activeTab === 'currency-input'" class="grid gap-10 pb-6">
          <CurrencyInputPlayground />
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

        <div v-if="activeTab === 'alerts'" class="grid gap-10 pb-6">
          <AlertPlayground />
        </div>

        <div v-if="activeTab === 'loading'" class="grid gap-10 pb-6">
          <LoadingPlayground />
        </div>

        <div v-if="activeTab === 'cards'" class="grid gap-10 py-6">
          <CardsPlayground />
        </div>

        <div v-if="activeTab === 'breadcrumbs'" class="grid gap-10 py-6">
          <BreadcrumbPlayground />
        </div>

        <div v-if="activeTab === 'avatars'" class="grid gap-10 py-6">
          <AvatarPlayground />
        </div>

        <div v-if="activeTab === 'otp'" class="grid gap-10 py-6">
          <OtpPlayground />
        </div>

        <div v-if="activeTab === 'tooltips'" class="grid gap-10 py-6">
          <TooltipPlayground />
        </div>

        <div v-if="activeTab === 'dropdowns'" class="grid gap-10 py-6">
          <DropdownPlayground />
        </div>

        <div v-if="activeTab === 'modals'" class="grid gap-10 py-6">
          <ModalPlayground />
        </div>

        <div v-if="activeTab === 'selects'" class="grid gap-10 py-6">
          <SelectPlayground />
        </div>

        <div v-if="activeTab === 'editors'" class="grid gap-10 py-6">
          <RichTextEditorPlayground />
        </div>
        <div v-if="activeTab === 'badges'" class="grid gap-10 py-6">
          <BadgePlayground />
        </div>
        <div v-if="activeTab === 'icons'" class="grid gap-10 py-6">
          <IconPlayground />
        </div>
        <div v-if="activeTab === 'errors'" class="grid gap-10 py-6">
          <ErrorPlayground />
        </div>
        </main>
      </div>
    </div>
  </AppLayout>
</template>
