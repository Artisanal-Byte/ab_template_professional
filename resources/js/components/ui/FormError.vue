<script setup lang="ts">
import { computed } from 'vue';
import Alert from '@/components/ui/Alert.vue';
import Icon from '@/components/Icon.vue';
import { cn } from '@/lib/utils';

const props = defineProps({
  error: {
    type: [String, Array, Object],
    default: '',
  },
  variant: {
    type: String,
    default: 'inline',
  },
  title: {
    type: String,
    default: 'Something went wrong.',
  },
  icon: {
    type: String,
    default: 'lucide:alert-circle',
  },
  class: {
    type: String,
    default: '',
  },
});

const isList = computed(
  () => Array.isArray(props.error) || (props.error && typeof props.error === 'object'),
);

const flattenErrors = (value: unknown): string[] => {
  if (!value) {
    return [];
  }
  if (typeof value === 'string') {
    return [value];
  }
  if (Array.isArray(value)) {
    return value.flatMap(flattenErrors).filter(Boolean);
  }
  if (typeof value === 'object') {
    return Object.values(value as Record<string, unknown>)
      .flatMap(flattenErrors)
      .filter(Boolean);
  }
  return [];
};

const messages = computed(() => flattenErrors(props.error));
const wrapperClass = computed(() => cn(props.class));
</script>

<template>
  <div v-if="messages.length" :class="wrapperClass">
    <Alert v-if="props.variant === 'alert'" variant="error">
      <template #title>
        <span class="flex items-center gap-2">
          <Icon :name="props.icon" class="size-4" />
          {{ props.title }}
        </span>
      </template>
      <template #description>
        <p v-if="!isList" class="text-sm">
          {{ messages[0] }}
        </p>
        <ul v-else class="list-inside list-disc text-sm">
          <li v-for="(message, index) in messages" :key="index">
            {{ message }}
          </li>
        </ul>
      </template>
    </Alert>

    <template v-else>
      <p v-if="!isList" class="text-sm text-error">
        {{ messages[0] }}
      </p>
      <ul v-else class="list-inside list-disc text-sm text-error">
        <li v-for="(message, index) in messages" :key="index">
          {{ message }}
        </li>
      </ul>
    </template>
  </div>
</template>
