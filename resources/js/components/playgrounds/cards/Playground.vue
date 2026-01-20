<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

// Playground-only footer layout choices, derived from Card footer slot usage patterns.
const footerOptions = [
  { label: 'None', value: 'none' },
  { label: 'Single', value: 'single' },
  { label: 'Split', value: 'split' },
];

const titleText = ref('Audit readiness');
const descriptionText = ref('Key checkpoints for compliance tracking.');
const bodyText = ref('Use this space to summarize the next steps or recent activity.');
const footerMode = ref('single');
const primaryLabel = ref('Review');
const secondaryLabel = ref('Preview');

const hasTitle = computed(() => titleText.value.trim().length > 0);
const hasDescription = computed(() => descriptionText.value.trim().length > 0);
const hasFooter = computed(() => footerMode.value !== 'none');

const tokens = [
  { token: '--card', role: 'Card background' },
  { token: '--card-foreground', role: 'Card text' },
  { token: '--card-border', role: 'Card border' },
  { token: '--border', role: 'Dividers' },
];

const componentProps = [
  {
    name: 'as',
    type: 'string | component',
    values: ['div', 'section', 'article'],
    defaultValue: 'div',
  },
  {
    name: 'headerClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
  {
    name: 'contentClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
  {
    name: 'footerClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
  {
    name: 'slots',
    type: 'slot',
    values: ['title', 'description', 'default', 'footer'],
    defaultValue: 'none',
  },
];

const buildImports = () => {
  const lines = ["import Card from '@/components/ui/Card.vue';"];
  if (hasFooter.value) {
    lines.push("import Button from '@/components/ui/Button.vue';");
  }
  return lines;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const lines = ['<Card>'];
  if (titleText.value.trim()) {
    lines.push(`  <template #title>${titleText.value}</template>`);
  }
  if (descriptionText.value.trim()) {
    lines.push(`  <template #description>${descriptionText.value}</template>`);
  }
  if (bodyText.value.trim()) {
    lines.push(`  <div>${bodyText.value}</div>`);
  }
  if (footerMode.value !== 'none') {
    lines.push('  <template #footer>');
    if (footerMode.value === 'single') {
      lines.push(`    <Button>${primaryLabel.value}</Button>`);
    } else {
      lines.push(`    <Button>${primaryLabel.value}</Button>`);
      lines.push(`    <Button variant="outline">${secondaryLabel.value}</Button>`);
    }
    lines.push('  </template>');
  }
  lines.push('</Card>');
  return lines.join('\n');
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Card</h2>
      <p class="text-sm text-foreground-subtle">
        Configure card content and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card>
        <template v-if="hasTitle" #title>{{ titleText }}</template>
        <template v-if="hasDescription" #description>{{ descriptionText }}</template>
        <div v-if="bodyText.trim()" class="text-sm text-foreground-subtle">{{ bodyText }}</div>
        <template v-if="hasFooter" #footer>
          <Button>{{ primaryLabel }}</Button>
          <Button v-if="footerMode === 'split'" variant="outline">{{ secondaryLabel }}</Button>
        </template>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="footerMode" name="card-footer" label="Footer" :options="footerOptions" />
        <div class="flex flex-col gap-3">
          <Label>Title</Label>
          <Input v-model="titleText" placeholder="Card title" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Description</Label>
          <Input v-model="descriptionText" placeholder="Description" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Body</Label>
          <Input v-model="bodyText" placeholder="Body text" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Primary button</Label>
          <Input v-model="primaryLabel" placeholder="Primary label" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>Secondary button</Label>
          <Input v-model="secondaryLabel" placeholder="Secondary label" />
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
