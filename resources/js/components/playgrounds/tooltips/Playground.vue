<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Tooltip from '@/components/ui/Tooltip.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

// Side options reflect Tooltip `side` prop.
const sideOptions = [
  { label: 'Top', value: 'top' },
  { label: 'Right', value: 'right' },
  { label: 'Bottom', value: 'bottom' },
  { label: 'Left', value: 'left' },
];

// Align options reflect Tooltip `align` prop.
const alignOptions = [
  { label: 'Start', value: 'start' },
  { label: 'Center', value: 'center' },
  { label: 'End', value: 'end' },
];

// Offset options map to Tooltip `sideOffset` prop values.
const offsetOptions = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

// Delay options map to Tooltip `delayDuration` prop values.
const delayOptions = [
  { label: '0ms', value: '0' },
  { label: '150ms', value: '150' },
  { label: '300ms', value: '300' },
];

// Arrow options toggle Tooltip arrow rendering.
const arrowOptions = [
  { label: 'Show', value: 'true' },
  { label: 'Hide', value: 'false' },
];

const side = ref('top');
const align = ref('center');
const sideOffset = ref('6');
const delayDuration = ref('0');
const showArrow = ref('true');
const triggerLabel = ref('Hover me');
const tooltipText = ref('Tooltip text goes here.');

const isArrowVisible = computed(() => showArrow.value === 'true');

const previewProps = computed(() => ({
  side: side.value,
  align: align.value,
  sideOffset: Number(sideOffset.value),
  delayDuration: Number(delayDuration.value),
  showArrow: isArrowVisible.value,
}));

const tokens = [
  { token: '--foreground', role: 'Tooltip background' },
  { token: '--background', role: 'Tooltip text' },
];

const componentProps = [
  {
    name: 'delayDuration',
    type: 'number',
    values: ['0', '150', '300'],
    defaultValue: '0',
  },
  {
    name: 'side',
    type: 'string',
    values: ['top', 'right', 'bottom', 'left'],
    defaultValue: 'top',
  },
  {
    name: 'align',
    type: 'string',
    values: ['start', 'center', 'end'],
    defaultValue: 'center',
  },
  {
    name: 'sideOffset',
    type: 'number',
    values: ['4', '6', '10'],
    defaultValue: '6',
  },
  {
    name: 'showArrow',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'true',
  },
  {
    name: 'contentClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
];

const buildImports = () => [
  "import Tooltip from '@/components/ui/Tooltip.vue';",
  "import Button from '@/components/ui/Button.vue';",
];

const buildTooltipAttrs = () => {
  const attrs = [];
  if (side.value !== 'top') {
    attrs.push(`side="${side.value}"`);
  }
  if (align.value !== 'center') {
    attrs.push(`align="${align.value}"`);
  }
  if (sideOffset.value !== '6') {
    attrs.push(`:side-offset="${sideOffset.value}"`);
  }
  if (delayDuration.value !== '0') {
    attrs.push(`:delay-duration="${delayDuration.value}"`);
  }
  if (!isArrowVisible.value) {
    attrs.push(':show-arrow="false"');
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildTooltipAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const resolvedLabel = triggerLabel.value || 'Hover me';
  const resolvedText = tooltipText.value || 'Tooltip text goes here.';
  return `<Tooltip${attrText}>\n  <template #trigger>\n    <Button variant="outline">${resolvedLabel}</Button>\n  </template>\n  ${resolvedText}\n</Tooltip>`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Tooltip playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure placement, delay, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="flex items-center justify-center">
        <template #title>Preview</template>
        <Tooltip v-bind="previewProps">
          <template #trigger>
            <Button variant="outline">{{ triggerLabel || 'Hover me' }}</Button>
          </template>
          {{ tooltipText || 'Tooltip text goes here.' }}
        </Tooltip>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup v-model="side" name="tooltip-side" label="Side" :options="sideOptions" />
        <RadioPillGroup v-model="align" name="tooltip-align" label="Align" :options="alignOptions" />
        <RadioPillGroup v-model="sideOffset" name="tooltip-offset" label="Offset" :options="offsetOptions" />
        <RadioPillGroup v-model="delayDuration" name="tooltip-delay" label="Delay" :options="delayOptions" />
        <RadioPillGroup v-model="showArrow" name="tooltip-arrow" label="Arrow" :options="arrowOptions" />
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Trigger label</Label>
          <Input v-model="triggerLabel" placeholder="Hover me" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Tooltip text</Label>
          <Input v-model="tooltipText" placeholder="Tooltip text goes here." />
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
