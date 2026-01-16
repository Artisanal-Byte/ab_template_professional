<script setup lang="ts">
import { computed, ref } from 'vue';
import Breadcrumb from '@/components/ui/Breadcrumb.vue';
import Button from '@/components/ui/Button.vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Icon from '@/components/Icon.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';

type BreadcrumbFormItem = {
  id: number;
  label: string;
  href: string;
};

type BreadcrumbPreviewItem = {
  label?: string;
  href?: string;
  current?: boolean;
  ellipsis?: boolean;
};

const patterns = [
  { label: 'Basic', value: 'basic' },
  { label: 'Ellipsis', value: 'ellipsis' },
];

const pattern = ref('basic');

const basicItems = ref<BreadcrumbFormItem[]>([
  { id: 1, label: 'Home', href: '/' },
  { id: 2, label: 'Library', href: '/library' },
  { id: 3, label: 'Data', href: '' },
]);

const ellipsisLeading = ref<BreadcrumbFormItem[]>([
  { id: 1, label: 'Home', href: '/' },
]);

const ellipsisTrailing = ref<BreadcrumbFormItem[]>([
  { id: 1, label: 'Settings', href: '/settings' },
  { id: 2, label: 'Appearance', href: '' },
]);

const nextId = ref(4);
const nextEllipsisId = ref(3);

const normalizeLabel = (value: string) => value.trim();
const slugify = (value: string) =>
  value
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');

const resolveHref = (label: string, href: string) => {
  if (href.trim()) {
    return href.trim();
  }
  const slug = slugify(label);
  return slug ? `/${slug}` : '';
};

const buildBasicItems = computed<BreadcrumbPreviewItem[]>(() => {
  const cleaned = basicItems.value
    .map((item) => ({
      label: normalizeLabel(item.label),
      href: item.href,
    }))
    .filter((item) => item.label.length > 0);

  if (!cleaned.length) {
    return [];
  }

  return cleaned.map((item, index) => {
    const isLast = index === cleaned.length - 1;
    return {
      label: item.label,
      href: !isLast ? resolveHref(item.label, item.href) : undefined,
      current: isLast,
    };
  });
});

const buildEllipsisItems = computed<BreadcrumbPreviewItem[]>(() => {
  const leading: BreadcrumbPreviewItem[] = ellipsisLeading.value
    .map((item) => ({
      label: normalizeLabel(item.label),
      href: item.href,
    }))
    .filter((item) => item.label.length > 0)
    .map((item) => ({
      label: item.label,
      href: resolveHref(item.label, item.href),
    }));

  const trailing = ellipsisTrailing.value
    .map((item) => ({
      label: normalizeLabel(item.label),
      href: item.href,
    }))
    .filter((item) => item.label.length > 0);

  const mappedTrailing = trailing.map((item, index) => {
    const isLast = index === trailing.length - 1;
    return {
      label: item.label,
      href: !isLast ? resolveHref(item.label, item.href) : undefined,
      current: isLast,
    };
  });

  const items: BreadcrumbPreviewItem[] = [...leading];
  if (items.length && mappedTrailing.length) {
    items.push({ ellipsis: true });
  }
  return [...items, ...mappedTrailing];
});

const items = computed(() =>
  pattern.value === 'ellipsis' ? buildEllipsisItems.value : buildBasicItems.value,
);

const addBasicItem = () => {
  basicItems.value = [
    ...basicItems.value,
    { id: nextId.value, label: '', href: '' },
  ];
  nextId.value += 1;
};

const removeBasicItem = (id: number) => {
  basicItems.value = basicItems.value.filter((item) => item.id !== id);
};

const addEllipsisLeading = () => {
  ellipsisLeading.value = [
    ...ellipsisLeading.value,
    { id: nextEllipsisId.value, label: '', href: '' },
  ];
  nextEllipsisId.value += 1;
};

const addEllipsisTrailing = () => {
  ellipsisTrailing.value = [
    ...ellipsisTrailing.value,
    { id: nextEllipsisId.value, label: '', href: '' },
  ];
  nextEllipsisId.value += 1;
};

const removeEllipsisLeading = (id: number) => {
  ellipsisLeading.value = ellipsisLeading.value.filter((item) => item.id !== id);
};

const removeEllipsisTrailing = (id: number) => {
  ellipsisTrailing.value = ellipsisTrailing.value.filter((item) => item.id !== id);
};

const tokens = [
  { token: '--foreground-subtle', role: 'Default text' },
  { token: '--foreground', role: 'Current item' },
  { token: '--foreground-disabled', role: 'Separator' },
];

const componentProps = [
  {
    name: 'items',
    type: 'array',
    values: ['{ label, href, current, ellipsis }'],
    defaultValue: '[]',
  },
  {
    name: 'slots',
    type: 'slot',
    values: ['item', 'separator', 'ellipsis'],
    defaultValue: 'none',
  },
];

const buildImports = () => ["import Breadcrumb from '@/components/ui/Breadcrumb.vue';"];
const importText = computed(() => buildImports().join('\n'));

const itemsSnippet = computed(() => {
  const list = items.value.map((item) => {
    if ('ellipsis' in item) {
      return '{ ellipsis: true }';
    }
    const parts = [`label: '${item.label}'`];
    if (item.href) {
      parts.push(`href: '${item.href}'`);
    }
    if (item.current) {
      parts.push('current: true');
    }
    return `{ ${parts.join(', ')} }`;
  });
  return `const breadcrumbItems = [\n  ${list.join(',\n  ')}\n];`;
});

const usageLine = computed(() => `${itemsSnippet.value}\n\n<Breadcrumb :items="breadcrumbItems" />`);

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
      <h2 class="text-2xl font-semibold">Breadcrumb</h2>
      <p class="text-sm text-foreground-subtle">
        Switch breadcrumb patterns and copy the generated snippet.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Breadcrumb :items="items" />
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="pattern" name="breadcrumb-pattern" label="Pattern" :options="patterns" />
      </div>

      <div v-if="pattern === 'basic'" class="grid gap-4">
        <div class="flex items-center justify-between gap-2">
          <Label>Breadcrumb items</Label>
          <Button variant="outline" size="sm" @click="addBasicItem">
            <IconLucidePlus class="h-4 w-4" />
            Add label
          </Button>
        </div>
        <div class="grid gap-3">
          <div
            v-for="(item, index) in basicItems"
            :key="item.id"
            class="grid gap-3 rounded-md border border-border-subtle bg-secondary-soft p-3 md:grid-cols-[1fr_1fr_auto]"
          >
            <div class="flex flex-col gap-2">
              <Label>Label {{ index + 1 }}</Label>
              <Input v-model="item.label" placeholder="Label" />
            </div>
            <div class="flex flex-col gap-2">
              <Label>Href (optional)</Label>
              <Input v-model="item.href" placeholder="/path" />
            </div>
            <div class="flex items-center justify-end">
              <Button
                variant="outline"
                size="sm"
                :disabled="basicItems.length <= 1"
                @click="removeBasicItem(item.id)"
              >
                <Icon name="lucide:trash-2" class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="grid gap-6">
        <div class="grid gap-4">
          <div class="flex items-center justify-between gap-2">
            <Label>Leading labels</Label>
            <Button variant="outline" size="sm" @click="addEllipsisLeading">
              <IconLucidePlus class="h-4 w-4" />
              Add label
            </Button>
          </div>
          <div class="grid gap-3">
            <div
              v-for="(item, index) in ellipsisLeading"
              :key="item.id"
              class="grid gap-3 rounded-md border border-border-subtle bg-secondary-soft p-3 md:grid-cols-[1fr_1fr_auto]"
            >
              <div class="flex flex-col gap-2">
                <Label>Label {{ index + 1 }}</Label>
                <Input v-model="item.label" placeholder="Label" />
              </div>
              <div class="flex flex-col gap-2">
                <Label>Href (optional)</Label>
                <Input v-model="item.href" placeholder="/path" />
              </div>
              <div class="flex items-center justify-end">
                <Button
                  variant="outline"
                  size="sm"
                  :disabled="ellipsisLeading.length <= 1"
                  @click="removeEllipsisLeading(item.id)"
                >
                  <Icon name="lucide:trash-2" class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
        </div>

        <div class="grid gap-4">
          <div class="flex items-center justify-between gap-2">
            <Label>Trailing labels</Label>
            <Button variant="outline" size="sm" @click="addEllipsisTrailing">
              <IconLucidePlus class="h-4 w-4" />
              Add label
            </Button>
          </div>
          <div class="grid gap-3">
            <div
              v-for="(item, index) in ellipsisTrailing"
              :key="item.id"
              class="grid gap-3 rounded-md border border-border-subtle bg-secondary-soft p-3 md:grid-cols-[1fr_1fr_auto]"
            >
              <div class="flex flex-col gap-2">
                <Label>Label {{ index + 1 }}</Label>
                <Input v-model="item.label" placeholder="Label" />
              </div>
              <div class="flex flex-col gap-2">
                <Label>Href (optional)</Label>
                <Input v-model="item.href" placeholder="/path" />
              </div>
              <div class="flex items-center justify-end">
                <Button
                  variant="outline"
                  size="sm"
                  :disabled="ellipsisTrailing.length <= 1"
                  @click="removeEllipsisTrailing(item.id)"
                >
                  <Icon name="lucide:trash-2" class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
          <p class="text-xs text-foreground-faint">
            The last trailing label becomes the current page automatically.
          </p>
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
