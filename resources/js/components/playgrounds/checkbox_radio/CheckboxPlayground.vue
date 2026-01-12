<script setup lang="ts">
import { computed, ref } from 'vue';
import Checkbox from '@/components/ui/Checkbox.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/checkbox_radio/DrawerSection.vue';

const checkedOptions = [
  { label: 'Unchecked', value: 'false' },
  { label: 'Checked', value: 'true' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const checked = ref('false');
const disabled = ref('false');
const labelText = ref('Remember me');
const inputName = ref('');
const inputId = ref('checkbox-playground');

const isChecked = computed(() => checked.value === 'true');
const isDisabled = computed(() => disabled.value === 'true');
const resolvedId = computed(() => inputId.value || 'checkbox-playground');
const showLabel = computed(() => labelText.value.trim().length > 0);

const tokens = [
  { token: '--border', role: 'Checkbox border' },
  { token: '--background', role: 'Checkbox background' },
  { token: '--primary', role: 'Checked state' },
  { token: '--focus-ring', role: 'Focus ring' },
  { token: '--foreground', role: 'Label text' },
];

const componentProps = [
  {
    name: 'v-model',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
  {
    name: 'native attributes',
    type: 'string | boolean',
    values: ['id', 'name', 'disabled'],
    defaultValue: '—',
  },
];

const buildImports = () => {
  const lines = ["import Checkbox from '@/components/ui/Checkbox.vue';"];
  if (showLabel.value) {
    lines.push("import Label from '@/components/ui/Label.vue';");
  }
  return lines;
};

const buildCheckboxAttrs = () => {
  const attrs = ['v-model="isChecked"'];
  if (resolvedId.value) {
    attrs.push(`id="${resolvedId.value}"`);
  }
  if (inputName.value) {
    attrs.push(`name="${inputName.value}"`);
  }
  if (isDisabled.value) {
    attrs.push('disabled');
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildCheckboxAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const checkboxLine = `<Checkbox${attrText} />`;
  if (!showLabel.value) {
    return checkboxLine;
  }
  return `<Label class="flex items-center gap-3" for="${resolvedId.value}">${checkboxLine}<span>${labelText.value}</span></Label>`;
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
      <h3 class="text-xl font-semibold">Checkbox playground</h3>
      <p class="text-sm text-foreground-subtle">
        Configure checkbox states and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup
          v-model="checked"
          name="checkbox-checked"
          label="Checked"
          :options="checkedOptions"
        />
        <RadioPillGroup
          v-model="disabled"
          name="checkbox-disabled"
          label="Disabled"
          :options="disabledOptions"
        />
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Label" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Input ID</Label>
          <Input v-model="inputId" placeholder="checkbox-playground" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Name</Label>
          <Input v-model="inputName" placeholder="Optional name" />
        </div>
      </div>

      <div class="grid gap-2">
        <Card content-class="flex items-center">
          <template #title>Preview</template>
          <Label class="flex items-center gap-3">
            <Checkbox
              v-model="isChecked"
              :id="resolvedId"
              :name="inputName || undefined"
              :disabled="isDisabled"
            />
            <span v-if="showLabel">{{ labelText }}</span>
          </Label>
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
