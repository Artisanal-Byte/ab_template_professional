<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions } from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';

// Transition options map to Dialog `transition` settings.
const transitionOptions = [
  { label: 'Scale', value: 'scale' },
  { label: 'Slide left', value: 'slide-left' },
];

// Modal options map to Dialog `modal` prop.
const modalOptions = booleanOptions;

// Close options control Dialog close affordances.
const closeOptions = [
  { label: 'Show', value: 'true' },
  { label: 'Hide', value: 'false' },
];

const sizeOptions = [
  { label: 'Small', value: 'sm' },
  { label: 'Medium', value: 'md' },
  { label: 'Large', value: 'lg' },
];

const transition = ref('scale');
const modal = ref('true');
const showClose = ref('true');
const size = ref('sm');
const triggerLabel = ref('Open dialog');

const isModal = computed(() => modal.value === 'true');
const isCloseVisible = computed(() => showClose.value === 'true');

const previewProps = computed(() => ({
  modal: isModal.value,
  transition: transition.value,
  size: size.value,
  showClose: isCloseVisible.value,
}));

const tokens = [
  { token: '--overlay', role: 'Backdrop' },
  { token: '--card', role: 'Dialog background' },
  { token: '--card-foreground', role: 'Dialog text' },
  { token: '--border', role: 'Dialog border' },
];

const componentProps = [
  {
    name: 'size',
    type: "'sm' | 'md' | 'lg'",
    values: ['sm', 'md', 'lg'],
    defaultValue: 'sm',
  },
  {
    name: 'modal',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'true',
  },
  {
    name: 'transition',
    type: 'string',
    values: ['scale', 'slide-left'],
    defaultValue: 'scale',
  },
  {
    name: 'showClose',
    type: 'boolean',
    values: ['true', 'false'],
    defaultValue: 'true',
  },
  {
    name: 'contentClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
  {
    name: 'bodyClass',
    type: 'string',
    values: ['custom classes'],
    defaultValue: 'empty',
  },
];

const buildImports = () => [
  "import Dialog from '@/components/ui/Dialog.vue';",
  "import Button from '@/components/ui/Button.vue';",
  "import Label from '@/components/ui/Label.vue';",
  "import Input from '@/components/ui/Input.vue';",
];

const buildDialogAttrs = () =>
  buildAttrList([
    { when: size.value !== 'sm', attr: `size="${size.value}"` },
    { when: !isModal.value, attr: ':modal="false"' },
    { when: transition.value !== 'scale', attr: `transition="${transition.value}"` },
    { when: !isCloseVisible.value, attr: ':show-close="false"' },
  ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildDialogAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const label = triggerLabel.value || 'Open dialog';
  return `<Dialog${attrText}>\n  <template #trigger>\n    <Button>${label}</Button>\n  </template>\n  <template #title>Invite collaborator</template>\n  <template #description>Send an invite to a teammate and assign a role.</template>\n  <div class="grid gap-3">\n    <div class="grid gap-2">\n      <Label for="dialog-email">Email address</Label>\n      <Input id="dialog-email" type="email" placeholder="name@company.com" />\n    </div>\n    <div class="grid gap-2">\n      <Label for="dialog-role">Role</Label>\n      <Input id="dialog-role" placeholder="Reviewer" />\n    </div>\n  </div>\n  <template #footer=\"{ close }\">\n    <Button variant=\"ghost\" @click=\"close\">Cancel</Button>\n    <Button variant=\"primary\">Send invite</Button>\n  </template>\n</Dialog>`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">Dialog playground</h2>
      <p class="text-sm text-foreground-subtle">
        Configure transitions, modal behavior, and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="flex items-center justify-center">
        <template #title>Preview</template>
        <Dialog v-bind="previewProps">
          <template #trigger>
            <Button>{{ triggerLabel || 'Open dialog' }}</Button>
          </template>
          <template #title>Invite collaborator</template>
          <template #description>
            Send an invite to a teammate and assign a role.
          </template>
          <div class="grid gap-3">
            <div class="grid gap-2">
              <Label for="dialog-email">Email address</Label>
              <Input id="dialog-email" type="email" placeholder="name@company.com" />
            </div>
            <div class="grid gap-2">
              <Label for="dialog-role">Role</Label>
              <Input id="dialog-role" placeholder="Reviewer" />
            </div>
          </div>
          <template #footer="{ close }">
            <Button variant="ghost" @click="close">Cancel</Button>
            <Button variant="primary">Send invite</Button>
          </template>
        </Dialog>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-4">
        <RadioPillGroup v-model="transition" name="dialog-transition" label="Transition" :options="transitionOptions" />
        <RadioPillGroup v-model="size" name="dialog-size" label="Size" :options="sizeOptions" />
        <RadioPillGroup v-model="modal" name="dialog-modal" label="Modal" :options="modalOptions" />
        <RadioPillGroup v-model="showClose" name="dialog-close" label="Close button" :options="closeOptions" />
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Trigger label</Label>
          <Input v-model="triggerLabel" placeholder="Open dialog" />
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
