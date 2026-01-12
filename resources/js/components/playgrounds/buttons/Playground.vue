<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import * as lucideIcons from 'lucide-vue-next';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';
import Icon from '@/components/Icon.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/buttons/DrawerSection.vue';

const variants = [
  { label: 'Primary', value: 'primary' },
  { label: 'Secondary', value: 'secondary' },
  { label: 'Outline', value: 'outline' },
  { label: 'Ghost', value: 'ghost' },
  { label: 'Success', value: 'success' },
  { label: 'Info', value: 'info' },
  { label: 'Warning', value: 'warning' },
  { label: 'Destructive', value: 'destructive' },
  { label: 'Link', value: 'link' },
];

const sizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
  { label: 'Icon', value: 'icon' },
];

const types = [
  { label: 'Button', value: 'button' },
  { label: 'Submit', value: 'submit' },
  { label: 'Reset', value: 'reset' },
];

const asOptions = [
  { label: 'Button', value: 'button' },
  { label: 'Link', value: 'Link' },
  { label: 'Anchor', value: 'a' },
  { label: 'Span', value: 'span' },
  { label: 'Div', value: 'div' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const variant = ref('primary');
const size = ref('md');
const type = ref('button');
const as = ref('button');
const disabled = ref('false');
const label = ref('Button');
const iconName = ref('Search');

const iconExclusions = new Set([
  'createLucideIcon',
  'LucideIcon',
  'LucideProps',
  'Icon',
  'default',
]);

const formatIconLabel = (name: string) =>
  name
    .replace(/([a-z0-9])([A-Z])/g, '$1 $2')
    .replace(/([A-Z])([A-Z][a-z])/g, '$1 $2');

const iconOptions = computed(() =>
  Object.keys(lucideIcons)
    .filter(
      (key) =>
        /^[A-Z]/.test(key) && !iconExclusions.has(key),
    )
    .sort((a, b) => a.localeCompare(b))
    .map((key) => ({
      label: formatIconLabel(key),
      value: key,
    })),
);

const tokens = [
  { token: '--primary', role: 'Primary background' },
  { token: '--primary-foreground', role: 'Primary text' },
  { token: '--secondary', role: 'Secondary background' },
  { token: '--secondary-strong', role: 'Secondary hover background' },
  { token: '--secondary-subtle', role: 'Outline hover background' },
  { token: '--border', role: 'Outline border' },
  { token: '--background', role: 'Outline background' },
  { token: '--foreground', role: 'Default text' },
  { token: '--success', role: 'Success background' },
  { token: '--info', role: 'Info background' },
  { token: '--warning', role: 'Warning background' },
  { token: '--error', role: 'Destructive background' },
  { token: '--focus-ring', role: 'Focus ring' },
];

const componentProps = [
  {
    name: 'variant',
    type: 'string',
    values: [
      'primary',
      'secondary',
      'outline',
      'ghost',
      'success',
      'info',
      'warning',
      'destructive',
      'link',
    ],
    defaultValue: 'primary',
  },
  {
    name: 'size',
    type: 'string',
    values: ['sm', 'md', 'lg', 'xl', 'icon'],
    defaultValue: 'md',
  },
  {
    name: 'type',
    type: 'string',
    values: ['button', 'submit', 'reset'],
    defaultValue: 'button',
    note: "Only applies when `as='button'`.",
  },
  {
    name: 'as',
    type: 'string | component',
    values: ['button', 'a', 'span', 'div', 'Link', 'any component'],
    defaultValue: 'button',
  },
  {
    name: 'disabled',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
];

const isButtonAs = computed(() => as.value === 'button');
const isDisabled = computed(() => disabled.value === 'true');
const isIconSize = computed(() => size.value === 'icon');
const resolvedIconName = computed(() => iconName.value || 'Search');
const resolvedAs = computed(() => (as.value === 'Link' ? Link : as.value));

const previewProps = computed(() => ({
  variant: variant.value,
  size: size.value,
  as: resolvedAs.value,
  type: type.value,
  disabled: isButtonAs.value ? isDisabled.value : false,
}));

const computedLabel = computed(() => label.value || 'Button');

const buildImports = () => {
  const lines = ["import Button from '@/components/ui/Button.vue';"];
  if (isIconSize.value) {
    lines.push("import Icon from '@/components/Icon.vue';");
  }
  return lines;
};

const buildButtonAttrs = () => {
  const attrs = [];
  if (variant.value !== 'primary') {
    attrs.push(`variant="${variant.value}"`);
  }
  if (size.value !== 'md') {
    attrs.push(`size="${size.value}"`);
  }
  if (as.value !== 'button') {
    attrs.push(`as="${as.value}"`);
  }
  if (as.value === 'button' && type.value !== 'button') {
    attrs.push(`type="${type.value}"`);
  }
  if (as.value === 'button' && isDisabled.value) {
    attrs.push('disabled');
  }
  return attrs;
};

const buildButtonContent = () =>
  isIconSize.value
    ? `<Icon name="${resolvedIconName.value}" />`
    : label.value || 'Button';

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildButtonAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  return `<Button${attrText}>${buildButtonContent()}</Button>`;
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

watch(as, (value) => {
  if (value !== 'button' && disabled.value === 'true') {
    disabled.value = 'false';
  }
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Button playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure button variants, sizes, and copy the generated snippet.
      </p>
    </div>
    <Card content-class="flex items-center justify-center">
      <template #title>Preview</template>
      <Button v-bind="previewProps">
        <Icon v-if="isIconSize" :name="resolvedIconName" class="h-4 w-4" />
        <span v-else>{{ computedLabel }}</span>
      </Button>
    </Card>
    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup v-model="variant" name="button-variant" label="Variant" :options="variants" />
        <RadioPillGroup v-model="size" name="button-size" label="Size" :options="sizes" />
        <RadioPillGroup v-model="as" name="button-as" label="As" :options="asOptions" />
        <RadioPillGroup v-model="type" name="button-type" label="Type" :options="types" :disabled="!isButtonAs" />
        <RadioPillGroup v-model="disabled" name="button-disabled" label="Disabled" :options="disabledOptions"
          :disabled="!isButtonAs" />
      </div>

      <div class="grid grid-cols-1 gap-4">
        <div v-if="isIconSize" class="flex flex-col gap-3">
          <Label>Icon</Label>
          <SearchAndSelect v-model="iconName" :options="iconOptions" width="full" placeholder="Select icon"
            search-placeholder="Search icons..." />
        </div>
        <div v-else class="flex flex-col gap-3">
          <Label>Label</Label>
          <Input v-model="label" placeholder="Button text" />
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
            <code class="rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
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
                <div v-if="prop.note" class="mt-2 text-xs text-foreground-faint">
                  {{ prop.note }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </DrawerSection>
    </div>
  </section>
</template>
