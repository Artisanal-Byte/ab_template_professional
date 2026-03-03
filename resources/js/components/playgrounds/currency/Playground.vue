<script setup lang="ts">
import { computed, ref } from 'vue';
import Label from '@/components/ui/Label.vue';
import Input from '@/components/ui/Input.vue';
import Card from '@/components/ui/Card.vue';
import CurrencyInput from '@/components/ui/CurrencyInput.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import {
  booleanOptions,
  sizeOptions,
  yesNoOptions,
} from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

const size = ref('md');
const locale = ref('en-IN');
const disabled = ref('false');
const required = ref('false');
const errorType = ref('none');
const labelText = ref('Amount');
const placeholder = ref('Enter amount');
const inputName = ref('');
const inputId = ref('currency-playground');
const inputValue = ref('100000');
const detailsOpen = ref(false);

const localeOptions = [
  { label: 'India (en-IN)', value: 'en-IN' },
  { label: 'United States (en-US)', value: 'en-US' },
  { label: 'United Kingdom (en-GB)', value: 'en-GB' },
  { label: 'Germany (de-DE)', value: 'de-DE' },
  { label: 'France (fr-FR)', value: 'fr-FR' },
  { label: 'Japan (ja-JP)', value: 'ja-JP' },
];

const errorOptions = [
  { label: 'None', value: 'none' },
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const isDisabled = computed(() => disabled.value === 'true');
const isRequired = computed(() => required.value === 'true');
const showLabel = computed(() => labelText.value.trim().length > 0);
const resolvedId = computed(() => inputId.value || 'currency-playground');

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'Please enter a valid amount.';
    case 'list':
      return ['Amount is required', 'Amount format is invalid'];
    case 'object':
      return { amount: ['Amount is required', 'Amount format is invalid'] };
    default:
      return '';
  }
});

const previewProps = computed(() => ({
  size: size.value,
  locale: locale.value,
  error: errorValue.value,
}));

const tokens = [
  { token: '--border', role: 'Input border' },
  { token: '--background', role: 'Input background' },
  { token: '--foreground', role: 'Input text' },
  { token: '--foreground-subtle', role: 'Currency symbol text' },
  { token: '--foreground-faint', role: 'Placeholder text' },
  { token: '--focus-ring', role: 'Focus ring' },
  { token: '--error', role: 'Error text' },
];

const componentProps = [
  {
    name: 'locale',
    type: 'string',
    values: ['en-IN', 'en-US', 'en-GB', 'de-DE', 'fr-FR', 'ja-JP'],
    defaultValue: 'en-IN',
  },
  {
    name: 'size',
    type: 'string',
    values: ['sm', 'md', 'lg'],
    defaultValue: 'md',
  },
  {
    name: 'error',
    type: 'string | array | object',
    values: ['string', 'array', 'object'],
    defaultValue: 'empty',
  },
  {
    name: 'native attributes',
    type: 'string | boolean',
    values: ['placeholder', 'name', 'id', 'disabled', 'required'],
    defaultValue: '—',
  },
];

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="Please enter a valid amount."';
    case 'list':
      return ':error="[\'Amount is required\', \'Amount format is invalid\']"';
    case 'object':
      return ':error="{ amount: [\'Amount is required\', \'Amount format is invalid\'] }"';
    default:
      return '';
  }
});

const buildCurrencyAttrs = () =>
  buildAttrList([
    { when: Boolean(resolvedId.value), attr: `id="${resolvedId.value}"` },
    { when: Boolean(inputName.value), attr: `name="${inputName.value}"` },
    { when: Boolean(placeholder.value), attr: `placeholder="${placeholder.value}"` },
    { when: size.value !== 'md', attr: `size="${size.value}"` },
    { when: locale.value !== 'en-IN', attr: `locale="${locale.value}"` },
    { when: Boolean(inputValue.value), attr: `value="${inputValue.value}"` },
    { when: isDisabled.value, attr: 'disabled' },
    { when: isRequired.value, attr: 'required' },
    { when: Boolean(errorSnippet.value), attr: errorSnippet.value },
  ]);

const importText = computed(() => {
  const lines = ["import CurrencyInput from '@/components/ui/CurrencyInput.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines.join('\n');
});

const usageText = computed(() => {
  const attrs = buildCurrencyAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const inputLine = `<CurrencyInput${attrText} />`;
  if (!showLabel.value) {
    return inputLine;
  }
  const labelLine = `<Label for="${resolvedId.value}">${labelText.value}</Label>`;
  return `${labelLine}\n${inputLine}`;
});
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Currency input playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure locale-aware currency formatting and copy the generated snippet.
      </p>
    </div>

    <Card content-class="grid gap-2">
      <template #title>Preview</template>
      <Label v-if="showLabel" :for="resolvedId">{{ labelText }}</Label>
      <CurrencyInput
        v-model="inputValue"
        :id="resolvedId"
        :name="inputName || undefined"
        :placeholder="placeholder"
        :disabled="isDisabled"
        :required="isRequired"
        v-bind="previewProps"
      />
    </Card>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup
          v-model="size"
          name="currency-size"
          label="Size"
          :options="sizeOptions"
        />
        <RadioPillGroup
          v-model="locale"
          name="currency-locale"
          label="Locale"
          :options="localeOptions"
        />
        <RadioPillGroup
          v-model="disabled"
          name="currency-disabled"
          label="Disabled"
          :options="booleanOptions"
        />
        <RadioPillGroup
          v-model="required"
          name="currency-required"
          label="Required"
          :options="yesNoOptions"
        />
        <RadioPillGroup
          v-model="errorType"
          name="currency-error"
          label="Error"
          :options="errorOptions"
        />
      </div>

      <div class="grid gap-4 md:grid-cols-3">
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Amount" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Enter amount" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Value</Label>
          <Input v-model="inputValue" placeholder="Optional value" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Input ID</Label>
          <Input v-model="inputId" placeholder="currency-playground" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Name</Label>
          <Input v-model="inputName" placeholder="Optional name" />
        </div>
      </div>

      <PlaygroundMetaPanel
        v-model:open="detailsOpen"
        :import-text="importText"
        :usage-text="usageText"
        :tokens="tokens"
        :component-props="componentProps"
      />
    </div>
  </section>
</template>
