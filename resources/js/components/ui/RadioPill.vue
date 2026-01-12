<script setup lang="ts">
import { computed } from 'vue';
import { getOptionLabel, getOptionValue } from '@/composables/useSelectDisplay';
import type { SelectOption } from '@/types';

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  option: {
    type: Object,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const model = defineModel({
  type: [String, Number],
  default: '',
});

const option = computed(() => props.option as SelectOption);
const optionValue = computed(() => getOptionValue(option.value));
const optionLabel = computed(() => getOptionLabel(option.value));
</script>

<template>
  <label class="cursor-pointer">
    <input
      v-model="model"
      class="peer sr-only"
      type="radio"
      :name="props.name"
      :value="optionValue"
      :disabled="props.disabled || option?.disabled"
    />
    <span
      class="inline-flex items-center rounded-full border border-border-subtle bg-background px-3 py-1.5 text-sm text-foreground transition peer-checked:border-primary peer-checked:bg-secondary-soft peer-checked:text-foreground peer-disabled:cursor-not-allowed peer-disabled:opacity-50 peer-focus-visible:ring-2 peer-focus-visible:ring-focus-ring"
    >
      <slot>{{ optionLabel }}</slot>
    </span>
  </label>
</template>
