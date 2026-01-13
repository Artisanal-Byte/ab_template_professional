<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/Button.vue';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const variants = [
  { label: 'Neutral', value: 'neutral' },
  { label: 'Primary', value: 'primary' },
  { label: 'Success', value: 'success' },
  { label: 'Warning', value: 'warning' },
  { label: 'Error', value: 'error' },
];

const asOptions = [
  { label: 'Span', value: 'span' },
  { label: 'Div', value: 'div' },
  { label: 'Link', value: 'Link' },
  { label: 'Anchor', value: 'a' },
];

const variant = ref('neutral');
const as = ref('span');
const label = ref('Badge');

const tokens = [
  { token: '--border-subtle', role: 'Neutral border' },
  { token: '--secondary-subtle', role: 'Neutral background' },
  { token: '--foreground', role: 'Neutral text' },
  { token: '--primary-border', role: 'Primary border' },
  { token: '--primary-soft', role: 'Primary background' },
  { token: '--primary', role: 'Primary text' },
  { token: '--success-border', role: 'Success border' },
  { token: '--success-soft', role: 'Success background' },
  { token: '--warning-border', role: 'Warning border' },
  { token: '--warning-soft', role: 'Warning background' },
  { token: '--error-border', role: 'Error border' },
  { token: '--error-soft', role: 'Error background' },
];

const componentProps = [
  {
    name: 'variant',
    type: 'string',
    values: ['neutral', 'primary', 'success', 'warning', 'error'],
    defaultValue: 'neutral',
  },
  {
    name: 'as',
    type: 'string | component',
    values: ['span', 'div', 'a', 'Link', 'any component'],
    defaultValue: 'span',
  },
];

const resolvedAs = computed(() => (as.value === 'Link' ? Link : as.value));

const previewProps = computed(() => ({
  variant: variant.value,
  as: resolvedAs.value,
}));

const computedLabel = computed(() => label.value || 'Badge');

const buildImports = () => ["import Badge from '@/components/ui/Badge.vue';"];

const buildBadgeAttrs = () => {
  const attrs = [];
  if (variant.value !== 'neutral') {
    attrs.push(`variant="${variant.value}"`);
  }
  if (as.value !== 'span') {
    attrs.push(`as="${as.value}"`);
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildBadgeAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  return `<Badge${attrText}>${computedLabel.value}</Badge>`;
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
      <h2 class="text-2xl font-semibold">Badge playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure badge variants, update the label, and copy the generated snippet.
      </p>
    </div>
    <div class="grid gap-2">
      <Card content-class="flex items-center justify-center">
        <template #title>Preview</template>
        <Badge v-bind="previewProps">{{ computedLabel }}</Badge>
      </Card>
    </div>
    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="variant" name="badge-variant" label="Variant" :options="variants" />
        <RadioPillGroup v-model="as" name="badge-as" label="As" :options="asOptions" />
        <div class="flex flex-col gap-3">
          <Label>Label</Label>
          <Input v-model="label" placeholder="Badge text" />
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
            <code class="rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
              <div v-for="prop in componentProps" :key="prop.name"
                class="rounded-md border border-border-subtle bg-secondary-soft p-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                  <span class="font-semibold text-foreground">{{ prop.name }}</span>
                  <span class="text-xs text-foreground-faint">{{ prop.type }}</span>
                </div>
                <div class="mt-2 text-xs text-foreground-faint">
                  Default: {{ prop.defaultValue }}
                </div>
                <div class="mt-2 flex flex-wrap gap-2 text-xs text-foreground">
                  <span v-for="value in prop.values" :key="value"
                    class="rounded-full border border-border-subtle bg-background px-2 py-0.5">
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
