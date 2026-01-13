<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';
import { createSelectOptions } from '@/components/playgrounds/selects/options';
import type { SelectOption } from '@/types';

const widthOptions = [
  { label: 'XS', value: 'xs' },
  { label: 'SM', value: 'sm' },
  { label: 'MD', value: 'md' },
  { label: 'LG', value: 'lg' },
  { label: 'XL', value: 'xl' },
  { label: 'Full', value: 'full' },
];

const sideOptions = [
  { label: 'Bottom', value: 'bottom' },
  { label: 'Top', value: 'top' },
];

const sideOffsets = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const loadingOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const createOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const optionsState = [
  { label: 'With options', value: 'default' },
  { label: 'Empty', value: 'empty' },
];

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
const selectedValue = ref('');
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

const buildSelectAttrs = () => {
  const attrs = ['v-model="selectedOption"'];
  attrs.push(optionMode.value === 'empty' ? ':options="[]"' : ':options="selectOptions"');
  if (inputName.value) {
    attrs.push(`name="${inputName.value}"`);
  }
  if (placeholder.value && placeholder.value !== 'Search and select') {
    attrs.push(`placeholder="${placeholder.value}"`);
  }
  if (searchPlaceholder.value && searchPlaceholder.value !== 'Search...') {
    attrs.push(`search-placeholder="${searchPlaceholder.value}"`);
  }
  if (width.value !== 'md') {
    attrs.push(`width="${width.value}"`);
  }
  if (side.value !== 'bottom') {
    attrs.push(`side="${side.value}"`);
  }
  if (sideOffset.value !== '6') {
    attrs.push(`:side-offset="${sideOffset.value}"`);
  }
  if (isLoading.value) {
    attrs.push('loading');
  }
  if (noResultsText.value && noResultsText.value !== 'No results') {
    attrs.push(`no-results-text="${noResultsText.value}"`);
  }
  if (canCreate.value) {
    attrs.push('allow-create');
    attrs.push('@create="handleCreate"');
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
  const attrs = buildSelectAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const selectLine = `<SearchAndSelect${attrText} />`;
  if (!showLabel.value) {
    return selectLine;
  }
  return `<Label>${labelText.value}</Label>\n${selectLine}`;
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
            <code class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
