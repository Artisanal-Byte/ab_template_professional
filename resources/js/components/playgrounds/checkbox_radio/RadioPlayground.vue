<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioGroup from '@/components/RadioGroup.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/checkbox_radio/DrawerSection.vue';

const options = [
  { label: 'Option A', value: 'option-a', description: 'Primary choice' },
  { label: 'Option B', value: 'option-b', description: 'Secondary choice' },
  { label: 'Option C', value: 'option-c', description: 'Fallback choice' },
];

const styleOptions = [
  { label: 'Standard', value: 'standard' },
  { label: 'Pill', value: 'pill' },
];

const layoutOptions = [
  { label: 'Vertical', value: 'vertical' },
  { label: 'Horizontal', value: 'horizontal' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

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
