<script setup lang="ts">
import { computed, ref } from 'vue';
import Alert from '@/components/ui/Alert.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const variants = [
  { label: 'Neutral', value: 'neutral' },
  { label: 'Info', value: 'info' },
  { label: 'Success', value: 'success' },
  { label: 'Warning', value: 'warning' },
  { label: 'Error', value: 'error' },
];

const variant = ref('neutral');
const titleText = ref('System update');
const descriptionText = ref('System updates are scheduled for tonight at 10 PM.');

const hasTitle = computed(() => titleText.value.trim().length > 0);
const hasDescription = computed(() => descriptionText.value.trim().length > 0);

const tokens = [
  { token: '--card', role: 'Surface background' },
  { token: '--card-foreground', role: 'Default text' },
  { token: '--border-subtle', role: 'Neutral border' },
  { token: '--info-soft', role: 'Info background' },
  { token: '--info-border', role: 'Info border' },
  { token: '--success-soft', role: 'Success background' },
  { token: '--success-border', role: 'Success border' },
  { token: '--warning-soft', role: 'Warning background' },
  { token: '--warning-border', role: 'Warning border' },
  { token: '--error-soft', role: 'Error background' },
  { token: '--error-border', role: 'Error border' },
];

const componentProps = [
  {
    name: 'variant',
    type: 'string',
    values: ['neutral', 'info', 'success', 'warning', 'error'],
    defaultValue: 'neutral',
  },
  {
    name: 'as',
    type: 'string | component',
    values: ['div', 'section', 'article'],
    defaultValue: 'div',
  },
  {
    name: 'slots',
    type: 'slot',
    values: ['title', 'description'],
    defaultValue: 'none',
  },
];

const buildImports = () => ["import Alert from '@/components/ui/Alert.vue';"];

const buildAlertAttrs = () => {
  const attrs = [];
  if (variant.value !== 'neutral') {
    attrs.push(`variant="${variant.value}"`);
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildAlertAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const lines = [`<Alert${attrText}>`];
  if (hasTitle.value) {
    lines.push(`  <template #title>${titleText.value}</template>`);
  }
  if (hasDescription.value) {
    lines.push(`  <template #description>${descriptionText.value}</template>`);
  }
  if (!hasTitle.value && !hasDescription.value) {
    lines.push('  Alert content');
  }
  lines.push('</Alert>');
  return lines.join('\n');
});

const copyImportLabel = ref('Copy');
const copyUsageLabel = ref('Copy');

const copyText = async (value: string, target: typeof copyImportLabel) => {
  try {
    await navigator.clipboard.writeText(value);
    target.value = 'Copied';
    window.setTimeout(() => {
      target.value = 'Copy';
    }, 1500);
  } catch {
    target.value = 'Copy failed';
    window.setTimeout(() => {
      target.value = 'Copy';
    }, 1500);
  }
};

const copyImport = () => {
  copyText(importText.value, copyImportLabel);
};

const copyUsage = () => {
  copyText(usageLine.value, copyUsageLabel);
};

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Alert</h2>
      <p class="text-sm text-foreground-subtle">
        Configure alert variants and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Alert :variant="variant">
          <template v-if="hasTitle" #title>{{ titleText }}</template>
          <template v-if="hasDescription" #description>{{ descriptionText }}</template>
        </Alert>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="variant" name="alert-variant" label="Variant" :options="variants" />
        <div class="flex flex-col gap-3">
          <Label>Title</Label>
          <Input v-model="titleText" placeholder="Optional title" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Description</Label>
          <Input v-model="descriptionText" placeholder="Optional description" />
        </div>
      </div>

      <div class="grid gap-2">
        <Label>Sample code</Label>
        <div class="grid gap-3 md:grid-cols-2">
          <div class="grid gap-2">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-foreground-subtle">Import</span>
              <Button variant="outline" size="sm" @click="copyImport">
                {{ copyImportLabel }}
              </Button>
            </div>
            <code class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
          {{ importText }}
        </code>
          </div>
          <div class="grid gap-2">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-foreground-subtle">Usage</span>
              <Button variant="outline" size="sm" @click="copyUsage">
                {{ copyUsageLabel }}
              </Button>
            </div>
            <code class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
          {{ usageLine }}
        </code>
          </div>
        </div>
      </div>

      <DrawerSection v-model:open="detailsOpen" title="Tokens & props">
        <div class="grid gap-6 md:grid-cols-2">
          <div>
            <Label class="text-lg">Tokens used</Label>
            <ul class="mt-3 text-foreground-subtle">
              <li class="grid grid-cols-2 items-center gap-2 font-semibold text-warning">
                <span>Role</span>
                <span>Token</span>
              </li>
              <li v-for="token in tokens" :key="token.token" class="grid grid-cols-2 gap-2">
                <span>{{ token.role }}</span>
                <code class="rounded bg-secondary-soft px-2 py-0.5 text-sm text-foreground">
                  {{ token.token }}
                </code>
              </li>
            </ul>
          </div>

          <div>
            <Label class="text-lg">Props ({{ componentProps.length }})</Label>
            <div class="mt-3 grid gap-3 text-sm text-foreground-subtle">
              <div
                v-for="prop in componentProps"
                :key="prop.name"
                class="rounded-md border border-border-subtle bg-secondary-soft p-3"
              >
                <div class="flex flex-wrap items-center justify-between gap-2">
                  <span class="font-semibold text-foreground">{{ prop.name }}</span>
                  <span class="text-xs text-foreground-faint">{{ prop.type }}</span>
                </div>
                <div class="mt-2 text-xs text-foreground-faint">
                  Default: {{ prop.defaultValue }}
                </div>
                <div class="mt-2 flex flex-wrap gap-2 text-xs text-foreground">
                  <span
                    v-for="value in prop.values"
                    :key="value"
                    class="rounded-full border border-border-subtle bg-background px-2 py-0.5"
                  >
                    {{ value }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </DrawerSection>
    </div>
  </section>
</template>
