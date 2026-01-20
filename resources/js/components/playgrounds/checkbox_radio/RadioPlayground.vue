<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioGroup from '@/components/RadioGroup.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions } from '@/components/playgrounds/options';

const options = [
  { label: 'Option A', value: 'option-a', description: 'Primary choice' },
  { label: 'Option B', value: 'option-b', description: 'Secondary choice' },
  { label: 'Option C', value: 'option-c', description: 'Fallback choice' },
];

// Radio styles reflect the `variant` prop on Radio.vue (default vs pill).
const styleOptions = [
  { label: 'Standard', value: 'standard' },
  { label: 'Pill', value: 'pill' },
];

// Layout options drive the preview container layout only.
const layoutOptions = [
  { label: 'Vertical', value: 'vertical' },
  { label: 'Horizontal', value: 'horizontal' },
];

// Disabled options map to the `disabled` attribute on Radio inputs.
const disabledOptions = booleanOptions;

const selected = ref('option-a');
const styleMode = ref('standard');
const layout = ref('vertical');
const disabled = ref('false');
const labelText = ref('Select an option');
const inputName = ref('radio-group');

const isDisabled = computed(() => disabled.value === 'true');
const showLabel = computed(() => labelText.value.trim().length > 0);
const isPillStyle = computed(() => styleMode.value === 'pill');

const previewProps = computed(() => ({
  direction: layout.value,
  disabled: isDisabled.value,
}));

const tokens = computed(() =>
  isPillStyle.value
    ? [
        { token: '--border-subtle', role: 'Pill border' },
        { token: '--background', role: 'Pill background' },
        { token: '--primary', role: 'Selected border' },
        { token: '--secondary-soft', role: 'Selected surface' },
        { token: '--focus-ring', role: 'Focus ring' },
        { token: '--foreground', role: 'Label text' },
      ]
    : [
        { token: '--border', role: 'Radio border' },
        { token: '--background', role: 'Radio background' },
        { token: '--primary', role: 'Checked state' },
        { token: '--focus-ring', role: 'Focus ring' },
        { token: '--foreground', role: 'Label text' },
      ],
);

const componentProps = computed(() =>
  isPillStyle.value
    ? [
        {
          name: 'v-model',
          type: 'string',
          values: ['selected value'],
          defaultValue: 'option-a',
        },
        {
          name: 'name',
          type: 'string',
          values: ['group name'],
          defaultValue: 'radio-group',
        },
        {
          name: 'options',
          type: 'array',
          values: ['{ label, value }'],
          defaultValue: '[]',
        },
        {
          name: 'disabled',
          type: 'boolean',
          values: ['true', 'false'],
          defaultValue: 'false',
        },
      ]
    : [
        {
          name: 'v-model',
          type: 'string',
          values: ['selected value'],
          defaultValue: 'option-a',
        },
        {
          name: 'name',
          type: 'string',
          values: ['group name'],
          defaultValue: 'radio-group',
        },
        {
          name: 'direction',
          type: 'string',
          values: ['vertical', 'horizontal'],
          defaultValue: 'vertical',
        },
        {
          name: 'disabled',
          type: 'boolean',
          values: ['true', 'false'],
          defaultValue: 'false',
        },
      ],
);

const buildImports = () => {
  const lines = [
    isPillStyle.value
      ? "import RadioPillGroup from '@/components/RadioPillGroup.vue';"
      : "import RadioGroup from '@/components/RadioGroup.vue';",
  ];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const buildStandardAttrs = () => {
  const attrs = ['v-model="selectedOption"', `name="${inputName.value || 'radio-group'}"`];
  if (layout.value !== 'vertical') {
    attrs.push(`direction="${layout.value}"`);
  }
  if (isDisabled.value) {
    attrs.push('disabled');
  }
  attrs.push(':options="radioOptions"');
  return attrs;
};

const buildPillAttrs = () => {
  const attrs = ['v-model="selectedOption"', `name="${inputName.value || 'radio-group'}"`];
  if (isDisabled.value) {
    attrs.push('disabled');
  }
  attrs.push(':options="radioOptions"');
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = isPillStyle.value ? buildPillAttrs() : buildStandardAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const groupLine = isPillStyle.value
    ? `<RadioPillGroup${attrText} />`
    : `<RadioGroup${attrText} />`;
  if (!showLabel.value) {
    return groupLine;
  }
  return `<Label>${labelText.value}</Label>\n${groupLine}`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h3 class="text-xl font-semibold">Radio playground</h3>
      <p class="text-sm text-foreground-subtle">
        Configure radio groups, layout, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup
          v-model="styleMode"
          name="radio-style"
          label="Style"
          :options="styleOptions"
        />
        <RadioPillGroup
          v-model="layout"
          name="radio-layout"
          label="Layout"
          :options="layoutOptions"
          :disabled="isPillStyle"
        />
        <RadioPillGroup
          v-model="disabled"
          name="radio-disabled"
          label="Disabled"
          :options="disabledOptions"
        />
        <div class="flex flex-col gap-3">
          <Label>Group label</Label>
          <Input v-model="labelText" placeholder="Select an option" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Group name</Label>
          <Input v-model="inputName" placeholder="radio-group" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Selected option</Label>
          <Input v-model="selected" placeholder="option-a" />
        </div>
      </div>

      <div class="grid gap-2">
        <Card content-class="grid gap-3">
          <template #title>Preview</template>
          <Label v-if="showLabel">{{ labelText }}</Label>
          <RadioGroup
            v-if="!isPillStyle"
            v-model="selected"
            :name="inputName || 'radio-group'"
            :options="options"
            v-bind="previewProps"
          />
          <RadioPillGroup
            v-else
            v-model="selected"
            :name="inputName || 'radio-group'"
            :options="options"
            :disabled="isDisabled"
          />
        </Card>
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
