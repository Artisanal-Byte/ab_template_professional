<script setup lang="ts">
import { TabsRoot } from 'reka-ui';
import type { TabsRootProps } from 'reka-ui';

// Slots:
// - default: Tabs content (TabsList + TabsTrigger + TabsContent children).
const props = defineProps<TabsRootProps & {
    contentClass?: string
}>();

const model = defineModel<string>('modelValue', {
    default: undefined,
});
</script>

<template>
    <TabsRoot
        v-model:orientation="props.orientation as TabsRootProps['orientation']"
        v-model:activation-mode="props.activationMode as TabsRootProps['activationMode']"
        :default-value="props.defaultValue"
        :model-value="model"
        v-bind="$attrs"
    >
        <slot />
    </TabsRoot>
</template>
