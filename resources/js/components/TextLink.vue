<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  href: {
    type: [String, Object],
    required: true,
  },
  tabindex: {
    type: Number,
    default: undefined,
  },
  method: {
    type: String,
    default: undefined,
  },
  as: {
    type: String,
    default: undefined,
  },
});

const linkAttrs = computed(() => ({
  href: props.href as any,
  method: props.method as any,
  as: props.as as any,
  tabindex: props.tabindex,
}));
</script>

<template>
  <Link
    v-bind="linkAttrs"
    class="text-foreground underline decoration-border underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current"
  >
    <slot />
  </Link>
</template>
