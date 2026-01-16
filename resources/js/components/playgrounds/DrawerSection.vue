<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/components/ui/Button.vue';
import Label from '@/components/ui/Label.vue';

const props = defineProps({
  title: {
    type: String,
    default: '',
  },
  toggleLabelOpen: {
    type: String,
    default: 'Hide details',
  },
  toggleLabelClosed: {
    type: String,
    default: 'Show details',
  },
  active: {
    type: Boolean,
    default: false,
  },
});

const open = defineModel('open', {
  type: Boolean,
  default: false,
});

const toggleLabel = computed(() =>
  open.value ? props.toggleLabelOpen : props.toggleLabelClosed,
);

const wrapperClass = computed(() =>
  open.value
    ? 'grid-rows-[1fr] opacity-100 translate-y-0'
    : 'grid-rows-[0fr] opacity-0 -translate-y-2 pointer-events-none',
);

const titleClass = computed(() =>
  props.active ? 'text-primary' : 'text-foreground',
);
</script>

<template>
  <div class="flex flex-wrap items-center justify-between gap-3">
    <Label class="text-lg" :class="titleClass">{{ props.title }}</Label>
    <Button variant="outline" size="sm" @click="open = !open">
      {{ toggleLabel }}
    </Button>
  </div>
  <div
    class="grid transition-[grid-template-rows,opacity,transform] duration-300 ease-out"
    :class="wrapperClass"
  >
    <div class="overflow-hidden">
      <slot />
    </div>
  </div>
</template>
