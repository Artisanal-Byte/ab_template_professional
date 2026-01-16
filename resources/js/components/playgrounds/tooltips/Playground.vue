<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Tooltip from '@/components/ui/Tooltip.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const sideOptions = [
  { label: 'Top', value: 'top' },
  { label: 'Right', value: 'right' },
  { label: 'Bottom', value: 'bottom' },
  { label: 'Left', value: 'left' },
];

const alignOptions = [
  { label: 'Start', value: 'start' },
  { label: 'Center', value: 'center' },
  { label: 'End', value: 'end' },
];

const offsetOptions = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

const delayOptions = [
  { label: '0ms', value: '0' },
  { label: '150ms', value: '150' },
  { label: '300ms', value: '300' },
];

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
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground"
            >
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground"
            >
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
