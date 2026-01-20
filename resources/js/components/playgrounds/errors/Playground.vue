<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import FormError from '@/components/ui/FormError.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

// FormError variant options reflect the `variant` prop.
const variants = [
  { label: 'Inline', value: 'inline' },
  { label: 'Alert', value: 'alert' },
];

// Error type options drive the shape passed to the `error` prop.
const errorTypes = [
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const variant = ref('inline');
const errorType = ref('string');
const message = ref('Something went wrong.');
const listItemOne = ref('Please provide a valid email address.');
const listItemTwo = ref('Password must be at least 8 characters.');
const objectKeyOne = ref('email');
const objectValueOne = ref('Email is required.');
const objectKeyTwo = ref('password');
const objectValueTwo = ref('Password is required.');
const title = ref('Something went wrong.');
const icon = ref('lucide:alert-circle');

const errorValue = computed(() => {
  if (errorType.value === 'list') {
    return [listItemOne.value, listItemTwo.value].filter(Boolean);
  }
  if (errorType.value === 'object') {
    const entries: Record<string, string> = {};
    if (objectKeyOne.value) {
      entries[objectKeyOne.value] = objectValueOne.value || 'Invalid value.';
    }
    if (objectKeyTwo.value) {
      entries[objectKeyTwo.value] = objectValueTwo.value || 'Invalid value.';
    }
    return entries;
  }
  return message.value;
});

const tokens = [
  { token: '--error', role: 'Inline text' },
  { token: '--error-border', role: 'Alert border' },
  { token: '--error-soft', role: 'Alert background' },
  { token: '--foreground', role: 'Alert title text' },
  { token: '--foreground-subtle', role: 'Alert description text' },
];

const componentProps = [
  {
    name: 'error',
    type: 'string | array | object',
    values: ['string', 'string[]', 'Record<string, string>'],
    defaultValue: "''",
  },
  {
    name: 'variant',
    type: 'string',
    values: ['inline', 'alert'],
    defaultValue: 'inline',
  },
  {
    name: 'title',
    type: 'string',
    values: ['Any string'],
    defaultValue: 'Something went wrong.',
  },
  {
    name: 'icon',
    type: 'string',
    values: ['iconify name'],
    defaultValue: 'lucide:alert-circle',
  },
  {
    name: 'class',
    type: 'string',
    values: ['Any class'],
    defaultValue: "''",
  },
];

const escapeString = (value: string) => value.replace(/'/g, "\\'");

const errorSnippet = computed(() => {
  if (errorType.value === 'list') {
    const values = [listItemOne.value, listItemTwo.value]
      .filter(Boolean)
      .map((item) => `'${escapeString(item)}'`);
    return `[${values.join(', ')}]`;
  }
  if (errorType.value === 'object') {
    const entries = [
      [objectKeyOne.value, objectValueOne.value],
      [objectKeyTwo.value, objectValueTwo.value],
    ]
      .filter(([key]) => Boolean(key))
      .map(([key, value]) => `${key}: '${escapeString(value || 'Invalid value.')}'`);
    return `{ ${entries.join(', ')} }`;
  }
  return `'${escapeString(message.value)}'`;
});

const buildImports = () => ["import FormError from '@/components/ui/FormError.vue';"];

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = [];
  if (errorType.value === 'string') {
    attrs.push(`error=\"${escapeString(message.value)}\"`);
  } else {
    attrs.push(`:error=\"${errorSnippet.value}\"`);
  }
  if (variant.value !== 'inline') {
    attrs.push(`variant=\"${variant.value}\"`);
  }
  if (variant.value === 'alert' && title.value !== 'Something went wrong.') {
    attrs.push(`title=\"${escapeString(title.value)}\"`);
  }
  if (variant.value === 'alert' && icon.value !== 'lucide:alert-circle') {
    attrs.push(`icon=\"${escapeString(icon.value)}\"`);
  }
  return `<FormError ${attrs.join(' ')} />`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Error playground</h2>
      <p class="text-sm text-foreground-subtle">
        Preview inline errors and alert-style summaries with the unified FormError component.
      </p>
    </div>

    <Card content-class="flex items-center justify-center">
      <template #title>Preview</template>
      <FormError
        :error="errorValue"
        :variant="variant"
        :title="title"
        :icon="icon"
      />
    </Card>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="variant" name="error-variant" label="Variant" :options="variants" />
        <RadioPillGroup v-model="errorType" name="error-type" label="Error type" :options="errorTypes" />
        <div v-if="variant === 'alert'" class="flex flex-col gap-3">
          <Label>Alert title</Label>
          <Input v-model="title" placeholder="Alert title" />
        </div>
      </div>

      <div v-if="variant === 'alert'" class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Icon name</Label>
          <Input v-model="icon" placeholder="lucide:alert-circle" />
        </div>
      </div>

      <div v-if="errorType === 'string'" class="flex flex-col gap-3">
        <Label>Message</Label>
        <Input v-model="message" placeholder="Something went wrong." />
      </div>

      <div v-else-if="errorType === 'list'" class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>List item 1</Label>
          <Input v-model="listItemOne" placeholder="First issue" />
        </div>
        <div class="flex flex-col gap-3">
          <Label>List item 2</Label>
          <Input v-model="listItemTwo" placeholder="Second issue" />
        </div>
      </div>

      <div v-else class="grid gap-4 md:grid-cols-2">
        <div class="grid gap-3">
          <Label>Object entry 1</Label>
          <Input v-model="objectKeyOne" placeholder="Field name" />
          <Input v-model="objectValueOne" placeholder="Field error message" />
        </div>
        <div class="grid gap-3">
          <Label>Object entry 2</Label>
          <Input v-model="objectKeyTwo" placeholder="Field name" />
          <Input v-model="objectValueTwo" placeholder="Field error message" />
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
