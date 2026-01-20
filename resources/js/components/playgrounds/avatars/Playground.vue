<script setup lang="ts">
import { computed, ref } from 'vue';
import Avatar from '@/components/ui/Avatar.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { sizeOptions } from '@/components/playgrounds/options';

// Avatar size options reflect Avatar.vue `size` prop.
const sizes = sizeOptions;

// Preview modes drive whether image/fallback/both are shown.
const modes = [
  { label: 'Image', value: 'image' },
  { label: 'Image + fallback', value: 'image-fallback' },
  { label: 'Fallback', value: 'fallback' },
];

const imageStates = [
  { label: 'Loaded', value: 'loaded' },
  { label: 'Broken', value: 'broken' },
];

const size = ref('md');
const mode = ref('image');
const imageState = ref('loaded');
const src = ref('https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=120&h=120&fit=facearea&facepad=2');
const alt = ref('User avatar');
const fallback = ref('JM');

const isImage = computed(() => mode.value === 'image' || mode.value === 'image-fallback');
const showFallback = computed(() => mode.value !== 'image');
const resolvedSrc = computed(() => {
  if (!isImage.value) {
    return '';
  }
  if (imageState.value === 'broken') {
    return 'https://invalid.domain/avatar.png';
  }
  return src.value;
});

const tokens = [
  { token: '--secondary-subtle', role: 'Avatar background' },
  { token: '--secondary-active', role: 'Fallback background' },
  { token: '--foreground', role: 'Fallback text' },
];

const componentProps = [
  {
    name: 'size',
    type: 'string',
    values: ['sm', 'md', 'lg'],
    defaultValue: 'md',
  },
  {
    name: 'src',
    type: 'string',
    values: ['image url'],
    defaultValue: 'empty',
  },
  {
    name: 'alt',
    type: 'string',
    values: ['alt text'],
    defaultValue: 'empty',
  },
  {
    name: 'fallback',
    type: 'string',
    values: ['initials'],
    defaultValue: 'empty',
  },
];

const buildImports = () => ["import Avatar from '@/components/ui/Avatar.vue';"];
const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  if (isImage.value) {
    const altAttr = alt.value ? ` alt="${alt.value}"` : '';
    const fallbackAttr = showFallback.value ? ` fallback="${fallback.value}"` : '';
    return `<Avatar size="${size.value}" src="${src.value}"${altAttr}${fallbackAttr} />`;
  }
  return `<Avatar size="${size.value}" fallback="${fallback.value}" />`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Avatar</h2>
      <p class="text-sm text-foreground-subtle">
        Switch avatar sizes and image fallback behavior.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Avatar
          :size="size"
          :src="resolvedSrc"
          :alt="alt"
          :fallback="showFallback ? fallback : ''"
        />
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="size" name="avatar-size" label="Size" :options="sizes" />
        <RadioPillGroup v-model="mode" name="avatar-mode" label="Mode" :options="modes" />
        <RadioPillGroup
          v-model="imageState"
          name="avatar-state"
          label="Image state"
          :options="imageStates"
          :disabled="!isImage"
        />
        <div class="flex flex-col gap-3">
          <Label>Image URL</Label>
          <Input v-model="src" placeholder="https://..." :disabled="!isImage" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Fallback</Label>
          <Input v-model="fallback" placeholder="Initials" :disabled="!showFallback" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Alt text</Label>
          <Input v-model="alt" placeholder="Alt text" :disabled="!isImage" />
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
