<script setup lang="ts">
import { computed, ref } from 'vue';
import DateTimePicker from '@/components/ui/DateTimePicker.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/date_time/DrawerSection.vue';

const modes = [
  { label: 'Date', value: 'date' },
  { label: 'Time', value: 'time' },
  { label: 'Date & Time', value: 'datetime' },
];

const sizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
];

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

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

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

const buildPickerAttrs = () => {
  const attrs = ['v-model="dateValue"'];
  if (resolvedId.value) {
    attrs.push(`id="${resolvedId.value}"`);
  }
  if (inputName.value) {
    attrs.push(`name="${inputName.value}"`);
  }
  if (mode.value !== 'date') {
    attrs.push(`mode="${mode.value}"`);
  }
  if (hasDate.value && dateFormat.value !== 'DD-MM-YYYY') {
    attrs.push(`format="${dateFormat.value}"`);
  }
  if (hasTime.value && timeFormat.value !== 'HH:mm') {
    attrs.push(`time-format="${timeFormat.value}"`);
  }
  if (hasTime.value && timeStep.value !== '15') {
    attrs.push(`:time-step="${timeStep.value}"`);
  }
  if (size.value !== 'md') {
    attrs.push(`size="${size.value}"`);
  }
  if (placeholder.value) {
    attrs.push(`placeholder="${placeholder.value}"`);
  }
  if (isDisabled.value) {
    attrs.push('disabled');
  }
  if (errorSnippet.value) {
    attrs.push(errorSnippet.value);
  }
  return attrs;
};

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
