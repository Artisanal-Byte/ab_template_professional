<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Skeleton from '@/components/ui/Skeleton.vue';
import Spinner from '@/components/ui/Spinner.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

// Skeleton type options map to the Skeleton component variants.
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
