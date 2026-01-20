<script setup lang="ts">
import { computed, ref } from 'vue';
import Alert from '@/components/ui/Alert.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

// Alert variant options reflect Alert.vue `variant` prop.
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

      <PlaygroundMetaPanel
        v-model:open="detailsOpen"
        :import-text="importText"
        :usage-text="usageLine"
        :tokens="tokens"
        :component-props="componentProps"
      />
    </div>
  </section>
</template>
