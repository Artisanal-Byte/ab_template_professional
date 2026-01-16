<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import DropdownMenu from '@/components/ui/DropdownMenu.vue';
import InlineDropdown from '@/components/ui/InlineDropdown.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Icon from '@/components/Icon.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const dropdownVariantOptions = [
  { label: 'Popover', value: 'popover' },
  { label: 'Inline', value: 'inline' },
];

const sideOptions = [
  { label: 'Bottom', value: 'bottom' },
  { label: 'Top', value: 'top' },
  { label: 'Right', value: 'right' },
  { label: 'Left', value: 'left' },
];

const alignOptions = [
  { label: 'Start', value: 'start' },
  { label: 'Center', value: 'center' },
  { label: 'End', value: 'end' },
];

const offsetOptions = [
  { label: '4px', value: '4' },
  { label: '6px', value: '6' },
  { label: '10px', value: '10' },
];

const widthOptions = [
  { label: 'XS', value: 'xs' },
  { label: 'SM', value: 'sm' },
  { label: 'MD', value: 'md' },
  { label: 'LG', value: 'lg' },
  { label: 'XL', value: 'xl' },
  { label: 'Full', value: 'full' },
];

const menuWidthOptions = [
  { label: 'Match trigger', value: 'auto' },
  { label: 'XS', value: 'xs' },
  { label: 'SM', value: 'sm' },
  { label: 'MD', value: 'md' },
  { label: 'LG', value: 'lg' },
  { label: 'XL', value: 'xl' },
  { label: 'Full', value: 'full' },
];

const variantOptions = [
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

const sizeOptions = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
  { label: 'XL', value: 'xl' },
  { label: 'Icon', value: 'icon' },
];

const disabledOptions = [
  { label: 'False', value: 'false' },
  { label: 'True', value: 'true' },
];

const dropdownVariant = ref('popover');
const side = ref('bottom');
const align = ref('start');
const sideOffset = ref('6');
const width = ref('md');
const menuWidth = ref('auto');
const triggerVariant = ref('secondary');
const triggerSize = ref('md');
const triggerDisabled = ref('false');
const triggerLabel = ref('Open menu');
const itemLabel = ref('');
const items = ref(['Item 1']);
const destructiveLabel = ref('Sign out');

const isDisabled = computed(() => triggerDisabled.value === 'true');

const menuItems = computed(() =>
  items.value.filter((item) => item.trim().length > 0),
);

const isInline = computed(() => dropdownVariant.value === 'inline');

const previewPopoverProps = computed(() => ({
  side: side.value,
  align: align.value,
  sideOffset: Number(sideOffset.value),
  width: width.value,
  menuWidth: menuWidth.value === 'auto' ? '' : menuWidth.value,
  triggerVariant: triggerVariant.value,
  triggerSize: triggerSize.value,
  triggerLabel: triggerLabel.value || 'Open menu',
  triggerDisabled: isDisabled.value,
}));

const previewInlineProps = computed(() => ({
  width: width.value,
  triggerVariant: triggerVariant.value,
  triggerSize: triggerSize.value,
  triggerLabel: triggerLabel.value || 'Open menu',
  triggerDisabled: isDisabled.value,
}));

const tokens = [
  { token: '--card', role: 'Menu background' },
  { token: '--card-foreground', role: 'Menu text' },
  { token: '--border-subtle', role: 'Container border' },
  { token: '--secondary-hover', role: 'Item hover' },
  { token: '--error', role: 'Destructive text' },
  { token: '--error-soft', role: 'Destructive hover' },
];

const popoverProps = [
  {
    name: 'side',
    type: 'string',
    values: ['top', 'bottom', 'left', 'right'],
    defaultValue: 'bottom',
  },
  {
    name: 'align',
    type: 'string',
    values: ['start', 'center', 'end'],
    defaultValue: 'start',
  },
  {
    name: 'sideOffset',
    type: 'number',
    values: ['4', '6', '10'],
    defaultValue: '6',
  },
  {
    name: 'width',
    type: 'string',
    values: ['xs', 'sm', 'md', 'lg', 'xl', 'full'],
    defaultValue: 'md',
  },
  {
    name: 'menuWidth',
    type: 'string',
    values: ['xs', 'sm', 'md', 'lg', 'xl', 'full'],
    defaultValue: 'match trigger',
  },
  {
    name: 'triggerLabel',
    type: 'string',
    values: ['Button label'],
    defaultValue: 'Open menu',
  },
  {
    name: 'triggerVariant',
    type: 'string',
    values: ['primary', 'secondary', 'outline', 'ghost', 'success', 'info', 'warning', 'destructive', 'link'],
    defaultValue: 'secondary',
  },
  {
    name: 'triggerSize',
    type: 'string',
    values: ['sm', 'md', 'lg', 'xl', 'icon'],
    defaultValue: 'md',
  },
  {
    name: 'triggerDisabled',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
];

const inlineProps = [
  {
    name: 'width',
    type: 'string',
    values: ['xs', 'sm', 'md', 'lg', 'xl', 'full'],
    defaultValue: 'md',
  },
  {
    name: 'triggerLabel',
    type: 'string',
    values: ['Button label'],
    defaultValue: 'Open menu',
  },
  {
    name: 'triggerVariant',
    type: 'string',
    values: ['primary', 'secondary', 'outline', 'ghost', 'success', 'info', 'warning', 'destructive', 'link'],
    defaultValue: 'secondary',
  },
  {
    name: 'triggerSize',
    type: 'string',
    values: ['sm', 'md', 'lg', 'xl', 'icon'],
    defaultValue: 'md',
  },
  {
    name: 'triggerDisabled',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
];

const componentProps = computed(() => (isInline.value ? inlineProps : popoverProps));

const addItem = () => {
  const label = itemLabel.value.trim();
  if (!label) {
    return;
  }
  items.value = [...items.value, label];
  itemLabel.value = '';
};

const removeItem = (index: number) => {
  items.value = items.value.filter((_, itemIndex) => itemIndex !== index);
};

const buildImports = () => {
  if (isInline.value) {
    return [
      "import InlineDropdown from '@/components/ui/InlineDropdown.vue';",
      "import Button from '@/components/ui/Button.vue';",
    ];
  }

  return [
    "import DropdownMenu from '@/components/ui/DropdownMenu.vue';",
    "import Button from '@/components/ui/Button.vue';",
  ];
};

const buildDropdownAttrs = () => {
  const attrs = [];
  if (!isInline.value) {
    if (side.value !== 'bottom') {
      attrs.push(`side="${side.value}"`);
    }
    if (align.value !== 'start') {
      attrs.push(`align="${align.value}"`);
    }
    if (sideOffset.value !== '6') {
      attrs.push(`:side-offset="${sideOffset.value}"`);
    }
  }
  if (width.value !== 'md') {
    attrs.push(`width="${width.value}"`);
  }
  if (!isInline.value && menuWidth.value !== 'auto') {
    attrs.push(`menu-width="${menuWidth.value}"`);
  }
  if (triggerVariant.value !== 'secondary') {
    attrs.push(`trigger-variant="${triggerVariant.value}"`);
  }
  if (triggerSize.value !== 'md') {
    attrs.push(`trigger-size="${triggerSize.value}"`);
  }
  if (isDisabled.value) {
    attrs.push('trigger-disabled');
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const buildMenuLines = () => {
  const lines: string[] = [];
  const entries = menuItems.value;
  entries.forEach((item) => {
    lines.push(
      `    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">${item}</button>`,
    );
  });
  if (entries.length) {
    lines.push('    <div class="my-1 h-px bg-border"></div>');
  }
  lines.push(
    `    <button class="w-full rounded-md px-2 py-1 text-left text-error hover:bg-error-soft">${destructiveLabel.value || 'Sign out'}</button>`,
  );
  return lines.length
    ? lines.join('\n')
    : '    <button class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover">Menu item</button>';
};

const usageLine = computed(() => {
  const attrs = buildDropdownAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const label = triggerLabel.value || 'Open menu';
  if (isInline.value) {
    return `<InlineDropdown${attrText} trigger-label="${label}">\n  <div class="grid gap-1">\n${buildMenuLines()}\n  </div>\n</InlineDropdown>`;
  }

  return `<DropdownMenu${attrText} trigger-label="${label}">\n  <div class="grid gap-1">\n${buildMenuLines()}\n  </div>\n</DropdownMenu>`;
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
      <h2 class="text-2xl font-semibold">Dropdown menu playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure placement, trigger styles, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="flex items-center justify-center">
        <template #title>Preview</template>
        <DropdownMenu v-if="!isInline" v-bind="previewPopoverProps">
          <div class="grid gap-1">
            <template v-for="(item, index) in menuItems" :key="`${item}-${index}`">
              <button
                class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover"
              >
                {{ item }}
              </button>
            </template>
            <div v-if="menuItems.length" class="my-1 h-px bg-border"></div>
            <button
              class="w-full rounded-md px-2 py-1 text-left text-error hover:bg-error-soft"
            >
              {{ destructiveLabel || 'Sign out' }}
            </button>
          </div>
        </DropdownMenu>
        <InlineDropdown v-else v-bind="previewInlineProps">
          <div class="grid gap-1">
            <template v-for="(item, index) in menuItems" :key="`${item}-${index}`">
              <button
                class="w-full rounded-md px-2 py-1 text-left hover:bg-secondary-hover"
              >
                {{ item }}
              </button>
            </template>
            <div v-if="menuItems.length" class="my-1 h-px bg-border"></div>
            <button
              class="w-full rounded-md px-2 py-1 text-left text-error hover:bg-error-soft"
            >
              {{ destructiveLabel || 'Sign out' }}
            </button>
          </div>
        </InlineDropdown>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup v-model="dropdownVariant" name="dropdown-variant" label="Variant" :options="dropdownVariantOptions" />
        <RadioPillGroup v-if="!isInline" v-model="side" name="dropdown-side" label="Side" :options="sideOptions" />
        <RadioPillGroup v-if="!isInline" v-model="align" name="dropdown-align" label="Align" :options="alignOptions" />
        <RadioPillGroup v-if="!isInline" v-model="sideOffset" name="dropdown-offset" label="Offset" :options="offsetOptions" />
        <RadioPillGroup v-model="width" name="dropdown-width" label="Width" :options="widthOptions" />
      </div>

      <div class="grid gap-4 md:grid-cols-5">
        <RadioPillGroup v-if="!isInline" v-model="menuWidth" name="dropdown-menu-width" label="Menu width" :options="menuWidthOptions" />
        <RadioPillGroup v-model="triggerVariant" name="dropdown-trigger-variant" label="Trigger variant" :options="variantOptions" />
        <RadioPillGroup v-model="triggerSize" name="dropdown-size" label="Size" :options="sizeOptions" />
        <RadioPillGroup v-model="triggerDisabled" name="dropdown-disabled" label="Disabled" :options="disabledOptions" />
        <div class="flex flex-col gap-3">
          <Label>Trigger label</Label>
          <Input v-model="triggerLabel" placeholder="Open menu" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-1">
        <div class="flex flex-col gap-3">
          <Label>Menu item</Label>
          <div class="flex items-center gap-2">
            <Input v-model="itemLabel" placeholder="Add menu item" />
            <Button variant="outline" size="icon" @click="addItem" aria-label="Add menu item">
              <Icon name="lucide:plus" class="h-4 w-4" />
            </Button>
          </div>
          <div class="flex flex-wrap gap-2">
            <div
              v-for="(item, index) in menuItems"
              :key="`menu-item-${item}-${index}`"
              class="flex items-center gap-2 rounded-full border border-border-subtle bg-secondary-soft px-3 py-1 text-xs text-foreground"
            >
              <span>{{ item }}</span>
              <button
                type="button"
                class="rounded-full p-0.5 text-foreground-faint transition hover:text-foreground"
                @click="removeItem(index)"
                aria-label="Remove menu item"
              >
                <Icon name="lucide:trash-2" class="h-3.5 w-3.5" />
              </button>
            </div>
          </div>
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground"
            >
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
