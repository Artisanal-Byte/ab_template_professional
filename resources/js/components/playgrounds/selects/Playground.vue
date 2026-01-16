<script setup lang="ts">
import StandardPlayground from '@/components/playgrounds/selects/StandardPlayground.vue';
import MultiSelectPlayground from '@/components/playgrounds/selects/MultiSelectPlayground.vue';
import SearchSelectPlayground from '@/components/playgrounds/selects/SearchSelectPlayground.vue';
</script>

<template>
  <div class="grid gap-10">
    <StandardPlayground />
    <MultiSelectPlayground />
    <SearchSelectPlayground />
  </div>
</template>
