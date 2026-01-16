<script setup lang="ts">
import { computed, ref } from 'vue';
import Avatar from '@/components/ui/Avatar.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const sizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
];

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
