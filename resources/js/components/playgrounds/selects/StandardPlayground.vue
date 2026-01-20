<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Select from '@/components/ui/Select.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { createSelectOptions } from '@/components/playgrounds/selects/options';
import { booleanOptions, widthOptions as sharedWidthOptions } from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Width options map to Select `width` prop (trigger + menu).
const widthOptions = sharedWidthOptions;

// Side options map to Select `side` prop.
const sideOptions = [
  { label: 'Bottom', value: 'bottom' },
  { label: 'Top', value: 'top' },
];

// Side offset options map to Select `sideOffset` prop values.
const sideOffsets = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

// Disabled options map to the native `disabled` attribute.
const disabledOptions = booleanOptions;

// Loading options map to Select `loading` prop.
const loadingOptions = booleanOptions;

const optionsState = [
  { label: 'With options', value: 'default' },
  { label: 'Empty', value: 'empty' },
];

// Error options drive the FormError preview state.
const errorOptions = [
  { label: 'None', value: 'none' },
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const optionMode = ref('default');
const width = ref('md');
const side = ref('bottom');
const sideOffset = ref('6');
const disabled = ref('false');
const loading = ref('false');
const errorType = ref('none');
const labelText = ref('Standard select');
const placeholder = ref('Select an option');
const noResultsText = ref('No results');
const inputName = ref('');
const selectedValue = ref('clinical');
const options = ref(createSelectOptions());

const isDisabled = computed(() => disabled.value === 'true');
const isLoading = computed(() => loading.value === 'true');
const showLabel = computed(() => labelText.value.trim().length > 0);

const previewOptions = computed(() =>
  optionMode.value === 'empty' ? [] : options.value,
);

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'Choose an option.';
    case 'list':
      return ['First error message', 'Second error message'];
    case 'object':
      return { select: ['First error message', 'Second error message'] };
    default:
      return '';
  }
});

const previewProps = computed(() => ({
  width: width.value,
  side: side.value,
  sideOffset: Number(sideOffset.value),
  loading: isLoading.value,
  noResultsText: noResultsText.value,
  error: errorValue.value,
}));

const tokens = [
  { token: '--border', role: 'Trigger border' },
  { token: '--background', role: 'Trigger background' },
  { token: '--foreground', role: 'Trigger text' },
  { token: '--foreground-faint', role: 'Placeholder text' },
  { token: '--card', role: 'Menu background' },
  { token: '--card-foreground', role: 'Menu text' },
  { token: '--secondary-hover', role: 'Option hover' },
  { token: '--secondary-active', role: 'Option selected' },
  { token: '--focus-ring', role: 'Focus ring' },
  { token: '--error', role: 'Error text' },
];

const componentProps = [
  {
    name: 'v-model',
    type: 'string | number',
    values: ['selected value'],
    defaultValue: 'empty',
  },
  {
    name: 'options',
    type: 'array',
    values: ['{ label, value, group }'],
    defaultValue: '[]',
  },
  {
    name: 'name',
    type: 'string',
    values: ['hidden input name'],
    defaultValue: 'empty',
  },
  {
    name: 'placeholder',
    type: 'string',
    values: ['Select an option'],
    defaultValue: 'Select an option',
  },
  {
    name: 'disabled',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
  {
    name: 'width',
    type: 'string',
    values: ['xs', 'sm', 'md', 'lg', 'xl', 'full'],
    defaultValue: 'md',
  },
  {
    name: 'side',
    type: 'string',
    values: ['bottom', 'top'],
    defaultValue: 'bottom',
  },
  {
    name: 'sideOffset',
    type: 'number',
    values: ['4', '6', '10'],
    defaultValue: '6',
  },
  {
    name: 'loading',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
  {
    name: 'noResultsText',
    type: 'string',
    values: ['No results'],
    defaultValue: 'No results',
  },
  {
    name: 'error',
    type: 'string | array | object',
    values: ['string', 'array', 'object'],
    defaultValue: 'empty',
  },
];

const buildImports = () => {
  const lines = ["import Select from '@/components/ui/Select.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="Choose an option."';
    case 'list':
      return ':error="[\'First error message\', \'Second error message\']"';
    case 'object':
      return ':error="{ select: [\'First error message\', \'Second error message\'] }"';
    default:
      return '';
  }
});

const buildSelectAttrs = () =>
  buildAttrList([
    { attr: 'v-model="selectedOption"' },
    {
      attr: optionMode.value === 'empty' ? ':options="[]"' : ':options="selectOptions"',
    },
    { when: Boolean(inputName.value), attr: `name="${inputName.value}"` },
    {
      when: Boolean(placeholder.value) && placeholder.value !== 'Select an option',
      attr: `placeholder="${placeholder.value}"`,
    },
    { when: width.value !== 'md', attr: `width="${width.value}"` },
    { when: side.value !== 'bottom', attr: `side="${side.value}"` },
    { when: sideOffset.value !== '6', attr: `:side-offset="${sideOffset.value}"` },
    { when: isLoading.value, attr: 'loading' },
    {
      when: Boolean(noResultsText.value) && noResultsText.value !== 'No results',
      attr: `no-results-text="${noResultsText.value}"`,
    },
    { when: isDisabled.value, attr: 'disabled' },
    { when: Boolean(errorSnippet.value), attr: errorSnippet.value },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildSelectAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const selectLine = `<Select${attrText} />`;
  if (!showLabel.value) {
    return selectLine;
  }
  return `<Label>${labelText.value}</Label>\n${selectLine}`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Standard select</h2>
      <p class="text-sm text-foreground-subtle">
        Configure single-select behavior and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Label v-if="showLabel">{{ labelText }}</Label>
        <Select
          v-model="selectedValue"
          :options="previewOptions"
          :placeholder="placeholder"
          :name="inputName"
          :disabled="isDisabled"
          v-bind="previewProps"
        />
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="width" name="select-width" label="Width" :options="widthOptions" />
        <RadioPillGroup v-model="side" name="select-side" label="Side" :options="sideOptions" />
        <RadioPillGroup v-model="sideOffset" name="select-offset" label="Offset" :options="sideOffsets" />
        <RadioPillGroup v-model="optionMode" name="select-options" label="Options" :options="optionsState" />
        <RadioPillGroup v-model="disabled" name="select-disabled" label="Disabled" :options="disabledOptions" />
        <RadioPillGroup v-model="loading" name="select-loading" label="Loading" :options="loadingOptions" />
        <RadioPillGroup v-model="errorType" name="select-error" label="Error" :options="errorOptions"  class="col-span-2" />
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Standard select" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Select an option" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>No results text</Label>
          <Input v-model="noResultsText" placeholder="No results" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Name</Label>
          <Input v-model="inputName" placeholder="Optional name" />
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
