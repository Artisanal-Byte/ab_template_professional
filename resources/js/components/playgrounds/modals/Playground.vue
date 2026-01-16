<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

const transitionOptions = [
  { label: 'Scale', value: 'scale' },
  { label: 'Slide left', value: 'slide-left' },
];

const modalOptions = [
  { label: 'True', value: 'true' },
  { label: 'False', value: 'false' },
];

const closeOptions = [
  { label: 'Show', value: 'true' },
  { label: 'Hide', value: 'false' },
];

const panelWidthOptions = [
  { label: '320px', value: 'w-[320px]' },
  { label: '420px', value: 'w-[420px]' },
  { label: '520px', value: 'w-[520px]' },
];

const transition = ref('scale');
const modal = ref('true');
const showClose = ref('true');
const panelWidth = ref('w-[320px]');
const triggerLabel = ref('Open dialog');

const isModal = computed(() => modal.value === 'true');
const isCloseVisible = computed(() => showClose.value === 'true');

const contentClass = computed(() =>
  transition.value === 'slide-left' ? panelWidth.value : '',
);

const previewProps = computed(() => ({
  modal: isModal.value,
  transition: transition.value,
  showClose: isCloseVisible.value,
  contentClass: contentClass.value,
}));

const tokens = [
  { token: '--overlay', role: 'Backdrop' },
  { token: '--card', role: 'Dialog background' },
  { token: '--card-foreground', role: 'Dialog text' },
  { token: '--border', role: 'Dialog border' },
];

const componentProps = [
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

const buildDialogAttrs = () => {
  const attrs = [];
  if (!isModal.value) {
    attrs.push(':modal="false"');
  }
  if (transition.value !== 'scale') {
    attrs.push(`transition="${transition.value}"`);
  }
  if (!isCloseVisible.value) {
    attrs.push(':show-close="false"');
  }
  if (contentClass.value) {
    attrs.push(`content-class="${contentClass.value}"`);
  }
  return attrs;
};

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = buildDialogAttrs();
  const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
  const label = triggerLabel.value || 'Open dialog';
  return `<Dialog${attrText}>\n  <template #trigger>\n    <Button>${label}</Button>\n  </template>\n  <template #title>Invite collaborator</template>\n  <template #description>Send an invite to a teammate and assign a role.</template>\n  <div class="grid gap-3">\n    <div class="grid gap-2">\n      <Label for="dialog-email">Email address</Label>\n      <Input id="dialog-email" type="email" placeholder="name@company.com" />\n    </div>\n    <div class="grid gap-2">\n      <Label for="dialog-role">Role</Label>\n      <Input id="dialog-role" placeholder="Reviewer" />\n    </div>\n  </div>\n  <template #footer=\"{ close }\">\n    <Button variant=\"ghost\" @click=\"close\">Cancel</Button>\n    <Button variant=\"primary\">Send invite</Button>\n  </template>\n</Dialog>`;
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
        <RadioPillGroup v-model="modal" name="dialog-modal" label="Modal" :options="modalOptions" />
        <RadioPillGroup v-model="showClose" name="dialog-close" label="Close button" :options="closeOptions" />
        <RadioPillGroup
          v-model="panelWidth"
          name="dialog-width"
          label="Panel width"
          :options="panelWidthOptions"
          :disabled="transition !== 'slide-left'"
        />
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="flex flex-col gap-3">
          <Label>Trigger label</Label>
          <Input v-model="triggerLabel" placeholder="Open dialog" />
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground"
            >
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
            <code
              class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground"
            >
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
