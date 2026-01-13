<script setup lang="ts">
import { computed, ref } from 'vue';
import Input from '@/components/ui/Input.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const sizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
];

const types = [
  { label: 'Text', value: 'text' },
  { label: 'Email', value: 'email' },
  { label: 'Password', value: 'password' },
  { label: 'Search', value: 'search' },
  { label: 'Number', value: 'number' },
  { label: 'URL', value: 'url' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const requiredOptions = [
  { label: 'No', value: 'false' },
  { label: 'Yes', value: 'true' },
];

const errorOptions = [
  { label: 'None', value: 'none' },
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const size = ref('md');
const type = ref('text');
const disabled = ref('false');
const required = ref('false');
const errorType = ref('none');
const labelText = ref('Label');
const placeholder = ref('Enter value');
const inputName = ref('');
const inputId = ref('input-playground');
const inputValue = ref('');

const isDisabled = computed(() => disabled.value === 'true');
const isRequired = computed(() => required.value === 'true');
const showLabel = computed(() => labelText.value.trim().length > 0);
const resolvedId = computed(() => inputId.value || 'input-playground');

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'This field is required.';
    case 'list':
      return ['First error message', 'Second error message'];
    case 'object':
      return { input: ['First error message', 'Second error message'] };
    default:
      return '';
  }
});

const previewProps = computed(() => ({
  size: size.value,
  error: errorValue.value,
}));

const tokens = [
  { token: '--border', role: 'Input border' },
  { token: '--background', role: 'Input background' },
  { token: '--foreground', role: 'Input text' },
  { token: '--foreground-faint', role: 'Placeholder text' },
  { token: '--focus-ring', role: 'Focus ring' },
  { token: '--error', role: 'Error text' },
];

const componentProps = [
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
    values: ['type', 'placeholder', 'name', 'id', 'disabled', 'required'],
    defaultValue: '—',
  },
];

const buildImports = () => {
  const lines = ["import Input from '@/components/ui/Input.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="This field is required."';
    case 'list':
      return ':error="[\'First error message\', \'Second error message\']"';
    case 'object':
      return ':error="{ input: [\'First error message\', \'Second error message\'] }"';
    default:
      return '';
  }
});

const buildInputAttrs = () => {
  const attrs = [];
  if (resolvedId.value) {
    attrs.push(`id="${resolvedId.value}"`);
  }
  if (inputName.value) {
    attrs.push(`name="${inputName.value}"`);
  }
  if (type.value !== 'text') {
    attrs.push(`type="${type.value}"`);
  }
  if (placeholder.value) {
    attrs.push(`placeholder="${placeholder.value}"`);
  }
  if (size.value !== 'md') {
    attrs.push(`size="${size.value}"`);
  }
  if (inputValue.value) {
    attrs.push(`value="${inputValue.value}"`);
  }
  if (isDisabled.value) {
    attrs.push('disabled');
  }
  if (isRequired.value) {
    attrs.push('required');
  }
  if (errorSnippet.value) {
    attrs.push(errorSnippet.value);
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildInputAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const inputLine = `<Input${attrText} />`;
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
      <h2 class="text-2xl font-semibold">Input playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure input sizes, states, and copy the generated snippet.
      </p>
    </div>
    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Label v-if="showLabel" :for="resolvedId">{{ labelText }}</Label>
        <Input v-model="inputValue" :id="resolvedId" :name="inputName || undefined" :type="type"
          :placeholder="placeholder" :disabled="isDisabled" :required="isRequired" v-bind="previewProps" />
      </Card>
    </div>
    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup v-model="size" name="input-size" label="Size" :options="sizes" />
        <RadioPillGroup v-model="type" name="input-type" label="Type" :options="types" />
        <RadioPillGroup v-model="disabled" name="input-disabled" label="Disabled" :options="disabledOptions" />
        <RadioPillGroup v-model="required" name="input-required" label="Required" :options="requiredOptions" />
        <RadioPillGroup v-model="errorType" name="input-error" label="Error" :options="errorOptions" />
      </div>

      <div class="grid gap-4 md:grid-cols-3">
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Label" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Enter value" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Value</Label>
          <Input v-model="inputValue" placeholder="Optional value" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Input ID</Label>
          <Input v-model="inputId" placeholder="input-playground" />
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
              <div v-for="prop in componentProps" :key="prop.name"
                class="rounded-md border border-border-subtle bg-secondary-soft p-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                  <span class="font-semibold text-foreground">{{ prop.name }}</span>
                  <span class="text-xs text-foreground-faint">{{ prop.type }}</span>
                </div>
                <div class="mt-2 text-xs text-foreground-faint">
                  Default: {{ prop.defaultValue }}
                </div>
                <div class="mt-2 flex flex-wrap gap-2 text-xs text-foreground">
                  <span v-for="value in prop.values" :key="value"
                    class="rounded-full border border-border-subtle bg-background px-2 py-0.5">
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
