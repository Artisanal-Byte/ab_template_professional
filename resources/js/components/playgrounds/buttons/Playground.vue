<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';
import Icon from '@/components/Icon.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions } from '@/components/playgrounds/options';
import { lucideIconOptions } from '@/components/playgrounds/iconOptions';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Button variant options reflect the Button.vue `variant` prop.
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

// Button size options reflect the Button.vue `size` prop.
const sizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
  { label: 'Icon', value: 'icon' },
];

// Button type options map to native `type` when `as="button"`.
const types = [
  { label: 'Button', value: 'button' },
  { label: 'Submit', value: 'submit' },
  { label: 'Reset', value: 'reset' },
];

// Render target options mirror Button.vue `as` prop support.
const asOptions = [
  { label: 'Button', value: 'button' },
  { label: 'Link', value: 'Link' },
  { label: 'Anchor', value: 'a' },
  { label: 'Span', value: 'span' },
  { label: 'Div', value: 'div' },
];

// Disabled options map to the native `disabled` attribute.
const disabledOptions = booleanOptions;

const variant = ref('primary');
const size = ref('md');
const type = ref('button');
const as = ref('button');
const disabled = ref('false');
const label = ref('Button');
const iconName = ref('search');

// Icon options are derived from the lucide icon set in @iconify/json.
const iconOptions = lucideIconOptions;

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
const resolvedIconName = computed(() => iconName.value || 'search');
const iconValue = computed(() => `lucide:${resolvedIconName.value}`);
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

const buildButtonAttrs = () =>
  buildAttrList([
    { when: variant.value !== 'primary', attr: `variant="${variant.value}"` },
    { when: size.value !== 'md', attr: `size="${size.value}"` },
    { when: as.value !== 'button', attr: `as="${as.value}"` },
    { when: as.value === 'button' && type.value !== 'button', attr: `type="${type.value}"` },
    { when: as.value === 'button' && isDisabled.value, attr: 'disabled' },
  ]);

const buildButtonContent = () =>
  isIconSize.value
    ? `<Icon name="${iconValue.value}" />`
    : label.value || 'Button';

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildButtonAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  return `<Button${attrText}>${buildButtonContent()}</Button>`;
});

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
        <Icon v-if="isIconSize" :name="iconValue" class="h-4 w-4" />
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
