<script setup lang="ts">
import { computed, ref } from 'vue';
import DateTimePicker from '@/components/ui/DateTimePicker.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions, sizeOptions } from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// DateTimePicker mode options reflect the `mode` prop.
const modes = [
  { label: 'Date', value: 'date' },
  { label: 'Time', value: 'time' },
  { label: 'Date & Time', value: 'datetime' },
];

// Input size options map to DateTimePicker `size` prop (Input sizing).
const sizes = sizeOptions;

const dateFormats = [
  { label: 'DD-MM-YYYY', value: 'DD-MM-YYYY' },
  { label: 'YYYY-MM-DD', value: 'YYYY-MM-DD' },
  { label: 'DD-MM-YY', value: 'DD-MM-YY' },
];

const timeFormats = [
  { label: '24h (HH:mm)', value: 'HH:mm' },
  { label: '12h (hh:mm A)', value: 'hh:mm A' },
];

const timeSteps = [
  { label: '5 min', value: '5' },
  { label: '10 min', value: '10' },
  { label: '15 min', value: '15' },
  { label: '30 min', value: '30' },
];

// Disabled options map to the native `disabled` state.
const disabledOptions = booleanOptions;

// Error options drive the FormError preview state.
const errorOptions = [
  { label: 'None', value: 'none' },
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const mode = ref('date');
const size = ref('md');
const dateFormat = ref('DD-MM-YYYY');
const timeFormat = ref('HH:mm');
const timeStep = ref('15');
const disabled = ref('false');
const errorType = ref('none');
const labelText = ref('Date');
const placeholder = ref('');
const inputName = ref('');
const inputId = ref('date-time-playground');
const value = ref('');

const isDisabled = computed(() => disabled.value === 'true');
const hasDate = computed(() => mode.value !== 'time');
const hasTime = computed(() => mode.value !== 'date');
const showLabel = computed(() => labelText.value.trim().length > 0);
const resolvedId = computed(() => inputId.value || 'date-time-playground');

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'Pick a valid date.';
    case 'list':
      return ['First error message', 'Second error message'];
    case 'object':
      return { field: ['First error message', 'Second error message'] };
    default:
      return '';
  }
});

const previewProps = computed(() => ({
  mode: mode.value,
  size: size.value,
  format: dateFormat.value,
  timeFormat: timeFormat.value,
  timeStep: Number(timeStep.value),
  placeholder: placeholder.value || undefined,
  error: errorValue.value,
}));

const tokens = [
  { token: '--border', role: 'Input border' },
  { token: '--background', role: 'Input background' },
  { token: '--foreground', role: 'Input text' },
  { token: '--foreground-faint', role: 'Placeholder text' },
  { token: '--focus-ring', role: 'Focus ring' },
  { token: '--card', role: 'Popover background' },
  { token: '--card-foreground', role: 'Popover text' },
  { token: '--secondary-soft', role: 'Picker surfaces' },
  { token: '--primary', role: 'Active states' },
  { token: '--error', role: 'Error text' },
];

const componentProps = [
  {
    name: 'mode',
    type: 'string',
    values: ['date', 'time', 'datetime'],
    defaultValue: 'date',
  },
  {
    name: 'format',
    type: 'string',
    values: ['DD-MM-YYYY', 'YYYY-MM-DD', 'DD-MM-YY'],
    defaultValue: 'DD-MM-YYYY',
  },
  {
    name: 'timeFormat',
    type: 'string',
    values: ['HH:mm', 'hh:mm A'],
    defaultValue: 'HH:mm',
  },
  {
    name: 'timeStep',
    type: 'number',
    values: ['5', '10', '15', '30'],
    defaultValue: '15',
  },
  {
    name: 'size',
    type: 'string',
    values: ['sm', 'md', 'lg'],
    defaultValue: 'md',
  },
  {
    name: 'placeholder',
    type: 'string',
    values: ['custom placeholder'],
    defaultValue: 'auto',
  },
  {
    name: 'error',
    type: 'string | array | object',
    values: ['string', 'array', 'object'],
    defaultValue: 'empty',
  },
];

const buildImports = () => {
  const lines = ["import DateTimePicker from '@/components/ui/DateTimePicker.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="Pick a valid date."';
    case 'list':
      return ':error="[\'First error message\', \'Second error message\']"';
    case 'object':
      return ':error="{ field: [\'First error message\', \'Second error message\'] }"';
    default:
      return '';
  }
});

const buildPickerAttrs = () =>
  buildAttrList([
    { attr: 'v-model="dateValue"' },
    { when: Boolean(resolvedId.value), attr: `id="${resolvedId.value}"` },
    { when: Boolean(inputName.value), attr: `name="${inputName.value}"` },
    { when: mode.value !== 'date', attr: `mode="${mode.value}"` },
    { when: hasDate.value && dateFormat.value !== 'DD-MM-YYYY', attr: `format="${dateFormat.value}"` },
    { when: hasTime.value && timeFormat.value !== 'HH:mm', attr: `time-format="${timeFormat.value}"` },
    { when: hasTime.value && timeStep.value !== '15', attr: `:time-step="${timeStep.value}"` },
    { when: size.value !== 'md', attr: `size="${size.value}"` },
    { when: Boolean(placeholder.value), attr: `placeholder="${placeholder.value}"` },
    { when: isDisabled.value, attr: 'disabled' },
    { when: Boolean(errorSnippet.value), attr: errorSnippet.value },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildPickerAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const inputLine = `<DateTimePicker${attrText} />`;
  if (!showLabel.value) {
    return inputLine;
  }
  const labelLine = `<Label for="${resolvedId.value}">${labelText.value}</Label>`;
  return `${labelLine}\n${inputLine}`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Date & time playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure date/time modes, formats, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup
          v-model="mode"
          name="dt-mode"
          label="Mode"
          :options="modes"
        />
        <RadioPillGroup
          v-model="size"
          name="dt-size"
          label="Size"
          :options="sizes"
        />
        <RadioPillGroup
          v-model="dateFormat"
          name="dt-date-format"
          label="Date format"
          :options="dateFormats"
          :disabled="!hasDate"
        />
        <RadioPillGroup
          v-model="timeFormat"
          name="dt-time-format"
          label="Time format"
          :options="timeFormats"
          :disabled="!hasTime"
        />
        <RadioPillGroup
          v-model="timeStep"
          name="dt-time-step"
          label="Time step"
          :options="timeSteps"
          :disabled="!hasTime"
        />
      </div>

      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup
          v-model="disabled"
          name="dt-disabled"
          label="Disabled"
          :options="disabledOptions"
        />
        <RadioPillGroup
          v-model="errorType"
          name="dt-error"
          label="Error"
          :options="errorOptions"
        />
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Label" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Optional placeholder" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Input ID</Label>
          <Input v-model="inputId" placeholder="date-time-playground" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Name</Label>
          <Input v-model="inputName" placeholder="Optional name" />
        </div>
      </div>

      <div class="grid gap-2">
        <Card content-class="grid gap-2">
          <template #title>Preview</template>
          <Label v-if="showLabel" :for="resolvedId">{{ labelText }}</Label>
          <DateTimePicker
            v-model="value"
            :id="resolvedId"
            :name="inputName || undefined"
            :disabled="isDisabled"
            v-bind="previewProps"
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
