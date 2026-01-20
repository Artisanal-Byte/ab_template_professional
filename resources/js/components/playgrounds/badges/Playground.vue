<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Badge from '@/components/ui/Badge.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Badge variant options reflect the Badge.vue `variant` prop.
const variants = [
  { label: 'Neutral', value: 'neutral' },
  { label: 'Primary', value: 'primary' },
  { label: 'Success', value: 'success' },
  { label: 'Warning', value: 'warning' },
  { label: 'Error', value: 'error' },
];

// Badge `as` options mirror supported element/component rendering targets.
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

const buildBadgeAttrs = () =>
  buildAttrList([
    { when: variant.value !== 'neutral', attr: `variant="${variant.value}"` },
    { when: as.value !== 'span', attr: `as="${as.value}"` },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildBadgeAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  return `<Badge${attrText}>${computedLabel.value}</Badge>`;
});

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
