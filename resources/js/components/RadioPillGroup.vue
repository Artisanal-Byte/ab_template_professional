<script setup lang="ts">
import { computed } from 'vue';
import Label from '@/components/ui/Label.vue';
import RadioPill from '@/components/ui/RadioPill.vue';
import type { SelectOption } from '@/types';

const props = defineProps({
  label: {
    type: String,
    default: '',
  },
  name: {
    type: String,
    required: true,
  },
  options: {
    type: Array,
    default: () => [],
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const model = defineModel({
  type: String,
  default: '',
});

const options = computed(() => props.options as SelectOption[]);
</script>

<template>
  <div class="flex flex-col gap-3">
    <Label v-if="props.label">{{ props.label }}</Label>
    <div class="flex flex-wrap gap-2">
      <RadioPill
        v-for="option in options"
        :key="String(option.value ?? option.label ?? '')"
        v-model="model"
        :name="props.name"
        :option="option"
        :disabled="props.disabled"
      />
    </div>
  </div>
</template>
