<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

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
