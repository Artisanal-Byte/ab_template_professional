<script setup lang="ts">
import { computed } from 'vue';
import Label from '@/components/ui/Label.vue';
import DrawerSection from '@/components/playgrounds/DrawerSection.vue';
import PlaygroundCodeBlock from '@/components/playgrounds/PlaygroundCodeBlock.vue';

type TokenItem = { token: string; role: string };
type PropItem = {
  name: string;
  type: string;
  values: string[];
  defaultValue: string;
  note?: string;
};

const props = defineProps({
  importText: {
    type: String,
    default: '',
  },
  usageText: {
    type: String,
    default: '',
  },
  tokens: {
    type: Array,
    default: () => [],
  },
  componentProps: {
    type: Array,
    default: () => [],
  },
  detailsTitle: {
    type: String,
    default: 'Tokens & props',
  },
});

const open = defineModel('open', {
  type: Boolean,
  default: false,
});

const tokens = computed(() => props.tokens as TokenItem[]);
const componentProps = computed(() => props.componentProps as PropItem[]);
</script>

<template>
  <div class="grid gap-2">
    <Label>Sample code</Label>
    <div class="grid gap-3 md:grid-cols-2">
      <PlaygroundCodeBlock label="Import" :code="props.importText" />
      <PlaygroundCodeBlock label="Usage" :code="props.usageText" />
    </div>
  </div>

  <DrawerSection v-model:open="open" :title="props.detailsTitle">
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
            <div v-if="prop.note" class="mt-2 text-xs text-foreground-faint">
              {{ prop.note }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </DrawerSection>
</template>
