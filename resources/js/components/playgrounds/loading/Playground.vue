<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Label from '@/components/ui/Label.vue';
import Skeleton from '@/components/ui/Skeleton.vue';
import Spinner from '@/components/ui/Spinner.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const typeOptions = [
  { label: 'Spinner', value: 'spinner' },
  { label: 'Skeleton', value: 'skeleton' },
];

const spinnerSizes = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
];

const skeletonVariants = [
  { label: 'Lines', value: 'lines' },
  { label: 'Card', value: 'card' },
  { label: 'Avatar', value: 'avatar' },
];

const loadingType = ref('spinner');
const spinnerSize = ref('md');
const skeletonVariant = ref('lines');

const isSpinner = computed(() => loadingType.value === 'spinner');
const isSkeleton = computed(() => loadingType.value === 'skeleton');

const tokens = [
  { token: '--foreground-subtle', role: 'Spinner color' },
  { token: '--secondary-subtle', role: 'Skeleton background' },
];

const componentProps = [
  {
    name: 'Spinner size',
    type: 'string',
    values: ['sm', 'md', 'lg'],
    defaultValue: 'md',
  },
  {
    name: 'Skeleton class',
    type: 'string',
    values: ['h-4 w-full', 'h-32 w-full', 'h-12 w-12 rounded-full'],
    defaultValue: 'h-4 w-full',
  },
];

const buildImports = () => {
  const lines = [];
  if (isSpinner.value) {
    lines.push("import Spinner from '@/components/ui/Spinner.vue';");
  }
  if (isSkeleton.value) {
    lines.push("import Skeleton from '@/components/ui/Skeleton.vue';");
  }
  return lines;
};

const buildUsage = () => {
  if (isSpinner.value) {
    const sizeAttr = spinnerSize.value !== 'md' ? ` size="${spinnerSize.value}"` : '';
    return `<Spinner${sizeAttr} />`;
  }

  if (skeletonVariant.value === 'card') {
    return '<Skeleton class="h-32 w-full" />';
  }
  if (skeletonVariant.value === 'avatar') {
    return '<Skeleton class="h-12 w-12 rounded-full" />';
  }
  return [
    '<div class="grid gap-2">',
    '  <Skeleton class="h-4 w-full" />',
    '  <Skeleton class="h-4 w-5/6" />',
    '  <Skeleton class="h-4 w-2/3" />',
    '</div>',
  ].join('\n');
};

const importText = computed(() => buildImports().join('\n'));
const usageLine = computed(() => buildUsage());

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
      <h2 class="text-2xl font-semibold">Loading</h2>
      <p class="text-sm text-foreground-subtle">
        Switch between spinner and skeleton states.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-3">
        <template #title>Preview</template>
        <div v-if="isSpinner" class="flex items-center gap-3">
          <Spinner :size="spinnerSize" />
          <span class="text-sm text-foreground-subtle">Loading...</span>
        </div>
        <div v-else class="grid gap-2">
          <template v-if="skeletonVariant === 'lines'">
            <Skeleton class="h-4 w-full" />
            <Skeleton class="h-4 w-5/6" />
            <Skeleton class="h-4 w-2/3" />
          </template>
          <Skeleton v-else-if="skeletonVariant === 'card'" class="h-32 w-full" />
          <Skeleton v-else class="h-12 w-12 rounded-full" />
        </div>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="loadingType" name="loading-type" label="Type" :options="typeOptions" />
        <RadioPillGroup
          v-model="spinnerSize"
          name="spinner-size"
          label="Spinner size"
          :options="spinnerSizes"
          :disabled="!isSpinner"
        />
        <RadioPillGroup
          v-model="skeletonVariant"
          name="skeleton-variant"
          label="Skeleton variant"
          :options="skeletonVariants"
          :disabled="!isSkeleton"
        />
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
