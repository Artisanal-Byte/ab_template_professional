<script setup lang="ts">
import { ref } from 'vue';
import Button from '@/components/ui/Button.vue';

const props = defineProps({
  label: {
    type: String,
    default: 'Code',
  },
  code: {
    type: String,
    default: '',
  },
});

const copyLabel = ref('Copy');

const copyText = async () => {
  try {
    await navigator.clipboard.writeText(props.code);
    copyLabel.value = 'Copied';
    window.setTimeout(() => {
      copyLabel.value = 'Copy';
    }, 1500);
  } catch {
    copyLabel.value = 'Copy failed';
    window.setTimeout(() => {
      copyLabel.value = 'Copy';
    }, 1500);
  }
};
</script>

<template>
  <div class="grid gap-2">
    <div class="flex items-center justify-between">
      <span class="text-sm font-medium text-foreground-subtle">{{ props.label }}</span>
      <Button variant="outline" size="sm" @click="copyText">
        {{ copyLabel }}
      </Button>
    </div>
    <code class="whitespace-pre-wrap rounded-md border border-border-subtle bg-secondary-soft p-3 text-sm text-foreground">
      {{ props.code }}
    </code>
  </div>
</template>
