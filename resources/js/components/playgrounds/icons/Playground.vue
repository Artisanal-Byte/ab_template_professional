<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Label from '@/components/ui/Label.vue';
import SearchAndSelect from '@/components/ui/SearchAndSelect.vue';
import NumberInput from '@/components/ui/NumberInput.vue';
import Icon from '@/components/Icon.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import PlaygroundCodeBlock from '@/components/playgrounds/PlaygroundCodeBlock.vue';
import { lucideIconOptions } from '@/components/playgrounds/iconOptions';

const iconName = ref('search');
const size = ref('20');

// Icon options are derived from the lucide icon set in @iconify/json.
const iconOptions = lucideIconOptions;

const resolvedIconName = computed(() => iconName.value || 'search');
const iconValue = computed(() => `lucide:${resolvedIconName.value}`);
const iconSize = computed(() => Number(size.value) || 16);

const tokens = [{ token: '--foreground', role: 'Icon color' }];

const componentProps = [
  {
    name: 'name',
    type: 'string',
    values: ['iconify icon name'],
    defaultValue: 'required',
  },
  {
    name: 'size',
    type: 'number | string',
    values: ['Any number'],
    defaultValue: '16',
  },
  {
    name: 'color',
    type: 'string',
    values: ['CSS color'],
    defaultValue: "''",
  },
  {
    name: 'strokeWidth',
    type: 'number | string',
    values: ['Any number'],
    defaultValue: '2',
  },
  {
    name: 'class',
    type: 'string',
    values: ['Any class'],
    defaultValue: "''",
  },
];

const buildImports = () => ["import Icon from '@/components/Icon.vue';"];

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = [`name=\"${iconValue.value}\"`];
  if (iconSize.value !== 16) {
    attrs.push(`:size=\"${iconSize.value}\"`);
  }
  return `<Icon ${attrs.join(' ')} />`;
});

const navSnippet = computed(
  () =>
    `const navItem = {\n  title: 'Dashboard',\n  href: '/dashboard',\n  icon: '${iconValue.value}',\n};`,
);

const inlineSnippet = computed(
  () =>
    `<InlineDropdown label=\"Quick actions\" icon=\"${iconValue.value}\">\n  <InlineDropdownItem label=\"New report\" />\n</InlineDropdown>`,
);

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Icon playground</h2>
      <p class="text-sm text-foreground-subtle">
        Pick an icon, set a size, and copy the usage snippets for common patterns.
      </p>
    </div>

    <Card content-class="flex items-center justify-center">
      <template #title>Preview</template>
      <Icon :name="iconValue" :size="iconSize" class="text-foreground" />
    </Card>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Icon</Label>
          <SearchAndSelect
            v-model="iconName"
            :options="iconOptions"
            width="full"
            placeholder="Select icon"
            search-placeholder="Search icons..."
          />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Size</Label>
          <NumberInput v-model="size" placeholder="20" />
        </div>
      </div>

      <PlaygroundMetaPanel
        v-model:open="detailsOpen"
        :import-text="importText"
        :usage-text="usageLine"
        :tokens="tokens"
        :component-props="componentProps"
      />

      <div class="grid gap-3 md:grid-cols-2">
        <PlaygroundCodeBlock label="Nav item icon string" :code="navSnippet" />
        <PlaygroundCodeBlock label="Inline dropdown icon prop" :code="inlineSnippet" />
      </div>
    </div>
  </section>
</template>
