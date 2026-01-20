<script setup lang="ts">
import { computed, ref } from 'vue';
import Checkbox from '@/components/ui/Checkbox.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions } from '@/components/playgrounds/options';

// Checked options map to the Checkbox v-model state.
const checkedOptions = [
  { label: 'Unchecked', value: 'false' },
  { label: 'Checked', value: 'true' },
];

// Disabled options map to the native `disabled` attribute.
const disabledOptions = booleanOptions;

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
