<script setup lang="ts">
import { computed } from 'vue';
import Label from '@/components/ui/Label.vue';
import Radio from '@/components/ui/Radio.vue';
import { getOptionLabel, getOptionValue, getOptionDescription } from '@/composables/useSelectDisplay';
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
  direction: {
    type: String,
    default: 'vertical',
  },
});

const model = defineModel({
  type: String,
  default: '',
});

const options = computed(() => props.options as SelectOption[]);

const groupClass = computed(() =>
  props.direction === 'horizontal'
    ? 'flex flex-wrap gap-4'
    : 'grid gap-2',
);

const getOptionId = (option: SelectOption) => {
  const rawValue = String(getOptionValue(option));
  const safeValue = rawValue
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');
  return `${props.name}-${safeValue || 'option'}`;
};
</script>

<template>
  <div class="flex flex-col gap-3">
    <Label v-if="props.label">{{ props.label }}</Label>
    <div :class="groupClass">
      <label
        v-for="option in options"
        :key="String(getOptionValue(option))"
        :for="getOptionId(option)"
        class="flex items-start gap-2 text-sm text-foreground"
      >
        <Radio
          :id="getOptionId(option)"
          v-model="model"
          :name="props.name"
          :value="String(getOptionValue(option))"
          :disabled="props.disabled || option.disabled"
        />
        <span class="leading-5">
          <span class="block font-medium">{{ getOptionLabel(option) }}</span>
          <span
            v-if="getOptionDescription(option)"
            class="block text-xs text-foreground-faint"
          >
            {{ getOptionDescription(option) }}
          </span>
        </span>
      </label>
    </div>
  </div>
</template>
