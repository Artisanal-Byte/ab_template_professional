<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { createSelectOptions } from '@/components/playgrounds/selects/options';
import { booleanOptions, widthOptions as sharedWidthOptions } from '@/components/playgrounds/options';
import type { SelectOption } from '@/types';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Width options map to SearchAndSelect `width` prop (trigger + menu).
const widthOptions = sharedWidthOptions;

// Side options map to SearchAndSelect `side` prop.
const sideOptions = [
  { label: 'Bottom', value: 'bottom' },
  { label: 'Top', value: 'top' },
];

// Side offset options map to SearchAndSelect `sideOffset` prop values.
const sideOffsets = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

// Disabled options map to the native `disabled` attribute.
const disabledOptions = booleanOptions;

// Loading options map to SearchAndSelect `loading` prop.
const loadingOptions = booleanOptions;

// Create options map to SearchAndSelect `allowCreate` behavior.
const createOptions = booleanOptions;

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
const allowCreate = ref('true');
const errorType = ref('none');
const labelText = ref('Search and select');
const placeholder = ref('Search and select');
const searchPlaceholder = ref('Search...');
const noResultsText = ref('No results');
const inputName = ref('');
const selectedValue = ref<string | number>('');
const baseOptions = ref(createSelectOptions());
const createdOptions = ref<SelectOption[]>([]);

const isDisabled = computed(() => disabled.value === 'true');
const isLoading = computed(() => loading.value === 'true');
const canCreate = computed(() => allowCreate.value === 'true');
const showLabel = computed(() => labelText.value.trim().length > 0);

const previewOptions = computed(() => {
  const combined = [...baseOptions.value, ...createdOptions.value];
  return optionMode.value === 'empty' ? createdOptions.value : combined;
});

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'Select a workflow.';
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
  allowCreate: canCreate.value,
}));

const tokens = [
  { token: '--border', role: 'Trigger border' },
  { token: '--background', role: 'Trigger background' },
  { token: '--foreground', role: 'Trigger text' },
  { token: '--foreground-faint', role: 'Placeholder text' },
  { token: '--card', role: 'Menu background' },
  { token: '--card-foreground', role: 'Menu text' },
  { token: '--secondary-hover', role: 'Option hover' },
  { token: '--secondary-active', role: 'Option selected/created hover' },
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
    values: ['Search and select'],
    defaultValue: 'Search and select',
  },
  {
    name: 'searchPlaceholder',
    type: 'string',
    values: ['Search...'],
    defaultValue: 'Search...',
  },
  {
    name: 'allowCreate',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
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
  {
    name: '@create',
    type: 'event',
    values: ['new label string'],
    defaultValue: 'optional',
  },
];

const buildImports = () => {
  const lines = ["import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="Select a workflow."';
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
      when: Boolean(placeholder.value) && placeholder.value !== 'Search and select',
      attr: `placeholder="${placeholder.value}"`,
    },
    {
      when: Boolean(searchPlaceholder.value) && searchPlaceholder.value !== 'Search...',
      attr: `search-placeholder="${searchPlaceholder.value}"`,
    },
    { when: width.value !== 'md', attr: `width="${width.value}"` },
    { when: side.value !== 'bottom', attr: `side="${side.value}"` },
    { when: sideOffset.value !== '6', attr: `:side-offset="${sideOffset.value}"` },
    { when: isLoading.value, attr: 'loading' },
    {
      when: Boolean(noResultsText.value) && noResultsText.value !== 'No results',
      attr: `no-results-text="${noResultsText.value}"`,
    },
    { when: canCreate.value, attr: 'allow-create' },
    { when: canCreate.value, attr: '@create="handleCreate"' },
    { when: isDisabled.value, attr: 'disabled' },
    { when: Boolean(errorSnippet.value), attr: errorSnippet.value },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildSelectAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const selectLine = `<SearchAndSelect${attrText} />`;
  if (!showLabel.value) {
    return selectLine;
  }
  return `<Label>${labelText.value}</Label>\n${selectLine}`;
});

const detailsOpen = ref(false);

const handleCreate = (value: string) => {
  const nextLabel = value.trim();
  if (!nextLabel) {
    return;
  }
  const normalizedValue = nextLabel.toLowerCase().replace(/\s+/g, '-');
  const existing = previewOptions.value.find(
    (option) => option.value === normalizedValue || option.label === nextLabel,
  );
  if (existing) {
    selectedValue.value = String(existing.value ?? existing.label ?? '');
    return;
  }
  const newOption: SelectOption = {
    label: nextLabel,
    value: normalizedValue,
    group: 'Created',
  };
  createdOptions.value = [...createdOptions.value, newOption];
  selectedValue.value = newOption.value ?? '';
};
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Search and select</h2>
      <p class="text-sm text-foreground-subtle">
        Configure search behavior, create actions, and copy the snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Label v-if="showLabel">{{ labelText }}</Label>
        <SearchAndSelect
          v-model="selectedValue"
          :options="previewOptions"
          :placeholder="placeholder"
          :search-placeholder="searchPlaceholder"
          :name="inputName"
          :disabled="isDisabled"
          @create="handleCreate"
          v-bind="previewProps"
        />
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="width" name="search-width" label="Width" :options="widthOptions" />
        <RadioPillGroup v-model="side" name="search-side" label="Side" :options="sideOptions" />
        <RadioPillGroup v-model="sideOffset" name="search-offset" label="Offset" :options="sideOffsets" />
        <RadioPillGroup v-model="optionMode" name="search-options" label="Options" :options="optionsState" />
        <RadioPillGroup v-model="disabled" name="search-disabled" label="Disabled" :options="disabledOptions" />
        <RadioPillGroup v-model="loading" name="search-loading" label="Loading" :options="loadingOptions" />
        <RadioPillGroup v-model="allowCreate" name="search-create" label="Allow create" :options="createOptions" />
        <RadioPillGroup v-model="errorType" name="search-error" label="Error" :options="errorOptions" />
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Search and select" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Search and select" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Search placeholder</Label>
          <Input v-model="searchPlaceholder" placeholder="Search..." />
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
