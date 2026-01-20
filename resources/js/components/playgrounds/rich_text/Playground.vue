<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import RichTextEditor from '@/components/ui/RichTextEditor.vue';
import { booleanOptions } from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Disabled options map to the RichTextEditor `disabled` prop.
const disabledOptions = booleanOptions;

// Mentions options map to the RichTextEditor `mentions` feature toggle.
const mentionsOptions = [
  { label: 'Enabled', value: 'true' },
  { label: 'Disabled', value: 'false' },
];

// Error options drive the FormError preview state.
const errorOptions = [
  { label: 'None', value: 'none' },
  { label: 'String', value: 'string' },
  { label: 'List', value: 'list' },
  { label: 'Object', value: 'object' },
];

const placeholder = ref('Write update notes, type @ to mention...');
const disabled = ref('false');
const mentionsEnabled = ref('true');
const errorType = ref('none');
const editorContent = ref('<p>Draft your next quality update here.</p>');
const disabledActions = ref<string[]>([]);

const mentionItems = [
  { id: '1', label: 'Avery Grant' },
  { id: '2', label: 'Jules Park' },
  { id: '3', label: 'Morgan Lee' },
];

const isDisabled = computed(() => disabled.value === 'true');
const hasMentions = computed(() => mentionsEnabled.value === 'true');

const errorValue = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'A summary is required.';
    case 'list':
      return ['First error message', 'Second error message'];
    case 'object':
      return { editor: ['First error message', 'Second error message'] };
    default:
      return '';
  }
});

const previewProps = computed(() => ({
  placeholder: placeholder.value,
  disabled: isDisabled.value,
  mentionItems: hasMentions.value ? mentionItems : [],
  disabledActions: disabledActions.value,
  error: errorValue.value,
}));

const tokens = [
  { token: '--card', role: 'Editor surface' },
  { token: '--card-foreground', role: 'Editor text' },
  { token: '--border', role: 'Borders' },
  { token: '--secondary-muted', role: 'Toolbar background' },
  { token: '--secondary-active', role: 'Active button states' },
  { token: '--accent', role: 'Links' },
];

const componentProps = [
  {
    name: 'v-model',
    type: 'string',
    values: ['HTML string'],
    defaultValue: 'empty',
  },
  {
    name: 'placeholder',
    type: 'string',
    values: ['Custom placeholder'],
    defaultValue: 'Start writing...',
  },
  {
    name: 'disabled',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'false',
  },
  {
    name: 'disabledActions',
    type: 'array',
    values: ['headers', 'fontSize', 'lists', 'blockquote', 'codeBlock', 'align', 'link', 'image', 'table'],
    defaultValue: '[]',
  },
  {
    name: 'mentionItems',
    type: 'array',
    values: ['{ id, label }'],
    defaultValue: '[]',
  },
  {
    name: 'uploadImage',
    type: 'function',
    values: ['(file) => url'],
    defaultValue: 'null',
  },
  {
    name: 'error',
    type: 'string | array | object',
    values: ['string', 'array', 'object'],
    defaultValue: 'empty',
  },
];

const buildImports = () => [
  "import RichTextEditor from '@/components/ui/RichTextEditor.vue';",
];

const errorSnippet = computed(() => {
  switch (errorType.value) {
    case 'string':
      return 'error="A summary is required."';
    case 'list':
      return ':error="[\'First error message\', \'Second error message\']"';
    case 'object':
      return ':error="{ editor: [\'First error message\', \'Second error message\'] }"';
    default:
      return '';
  }
});

const buildEditorAttrs = () =>
  buildAttrList([
    { attr: 'v-model="editorHtml"' },
    {
      when: Boolean(placeholder.value) && placeholder.value !== 'Start writing...',
      attr: `placeholder="${placeholder.value}"`,
    },
    { when: isDisabled.value, attr: 'disabled' },
    { when: hasMentions.value, attr: ':mention-items="mentionItems"' },
    {
      when: Boolean(disabledActions.value.length),
      attr: `:disabled-actions="[${disabledActions.value.map((item) => `'${item}'`).join(', ')}]"`,
    },
    { when: Boolean(errorSnippet.value), attr: errorSnippet.value },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildEditorAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  return `<RichTextEditor${attrText} />`;
});

// Toolbar action presets map to available RTE actions in RichTextEditor.
const actionOptions = [
  { id: 'headers', label: 'Header group' },
  { id: 'fontSize', label: 'Font size' },
  { id: 'lists', label: 'Lists group' },
  { id: 'blockquote', label: 'Blockquote' },
  { id: 'codeBlock', label: 'Code block' },
  { id: 'align', label: 'Align group' },
  { id: 'link', label: 'Link' },
  { id: 'image', label: 'Image' },
  { id: 'table', label: 'Table' },
];

const isActionSelected = (id: string) =>
  disabledActions.value.includes(id);

const toggleAction = (id: string) => {
  if (isActionSelected(id)) {
    disabledActions.value = disabledActions.value.filter((item) => item !== id);
    return;
  }
  disabledActions.value = [...disabledActions.value, id];
};

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Rich text editor playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure editor states, mentions, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-3">
        <template #title>Preview</template>
        <RichTextEditor v-model="editorContent" v-bind="previewProps" />
        <div class="rounded-lg border border-border bg-card p-4 text-sm text-foreground-muted">
          <div class="mb-2 text-xs uppercase tracking-wide text-foreground-disabled">HTML output</div>
          <pre class="whitespace-pre-wrap break-words">{{ editorContent }}</pre>
        </div>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="disabled" name="rte-disabled" label="Disabled" :options="disabledOptions" />
        <RadioPillGroup v-model="mentionsEnabled" name="rte-mentions" label="Mentions" :options="mentionsOptions" />
        <RadioPillGroup v-model="errorType" name="rte-error" label="Error" :options="errorOptions" />
        <div class="flex flex-col gap-3">
          <Label>Placeholder</Label>
          <Input v-model="placeholder" placeholder="Start writing..." />
        </div>
      </div>

      <div class="grid gap-2">
        <Label>Disabled actions</Label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="option in actionOptions"
            :key="option.id"
            type="button"
            class="rounded-full border px-3 py-1 text-xs font-medium transition"
            :class="isActionSelected(option.id)
              ? 'border-primary bg-primary-soft text-primary'
              : 'border-border-subtle bg-background text-foreground'"
            @click="toggleAction(option.id)"
          >
            {{ option.label }}
          </button>
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
