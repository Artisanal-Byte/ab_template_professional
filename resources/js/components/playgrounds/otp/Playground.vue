<script setup lang="ts">
import { computed, ref } from 'vue';
import Card from '@/components/ui/Card.vue';
import Input from '@/components/ui/Input.vue';
import InputOTP from '@/components/ui/InputOTP.vue';
import Label from '@/components/ui/Label.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';

const lengths = [
  { label: '4 digits', value: '4' },
  { label: '6 digits', value: '6' },
];

const separators = [
  { label: 'None', value: '0' },
  { label: 'After 2', value: '2' },
  { label: 'After 3', value: '3' },
];

const otpLength = ref('6');
const separatorAt = ref('0');
const labelText = ref('Verification code');
const otpValue = ref('');

const resolvedLength = computed(() => Number(otpLength.value));
const resolvedSeparator = computed(() => Number(separatorAt.value));
const showLabel = computed(() => labelText.value.trim().length > 0);

const tokens = [
  { token: '--border', role: 'Input border' },
  { token: '--background', role: 'Input background' },
  { token: '--foreground', role: 'Input text' },
  { token: '--focus-ring', role: 'Focus ring' },
];

const componentProps = [
  {
    name: 'v-model',
    type: 'string',
    values: ['otp value'],
    defaultValue: 'empty',
  },
  {
    name: 'otpLength',
    type: 'number',
    values: ['4', '6'],
    defaultValue: '6',
  },
  {
    name: 'separatorAt',
    type: 'number',
    values: ['0', '2', '3'],
    defaultValue: '0',
  },
];

const buildImports = () => ["import InputOTP from '@/components/ui/InputOTP.vue';"];
const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
  const attrs = [`:otp-length="${resolvedLength.value}"`];
  if (resolvedSeparator.value) {
    attrs.push(`:separator-at="${resolvedSeparator.value}"`);
  }
  return `<InputOTP v-model="otpValue" ${attrs.join(' ')} />`;
});

const detailsOpen = ref(false);
</script>

<template>
  <section class="grid gap-6">
    <div class="grid gap-2">
      <h2 class="text-2xl font-semibold">OTP input</h2>
      <p class="text-sm text-foreground-subtle">
        Configure OTP length and separator placement.
      </p>
    </div>

    <div class="grid gap-2">
      <Card content-class="grid gap-2">
        <template #title>Preview</template>
        <Label v-if="showLabel">{{ labelText }}</Label>
        <InputOTP
          v-model="otpValue"
          :otp-length="resolvedLength"
          :separator-at="resolvedSeparator"
        />
        <div class="text-xs text-foreground-faint">
          Value: {{ otpValue || '-' }}
        </div>
      </Card>
    </div>

    <div class="grid gap-6">
      <div class="grid gap-4 md:grid-cols-3">
        <RadioPillGroup v-model="otpLength" name="otp-length" label="Length" :options="lengths" />
        <RadioPillGroup v-model="separatorAt" name="otp-separator" label="Separator" :options="separators" />
        <div class="flex flex-col gap-3">
          <Label>Label text</Label>
          <Input v-model="labelText" placeholder="Verification code" />
        </div>
      </div>

      <PlaygroundMetaPanel
        v-model:open="detailsOpen"
        :import-text="importText"
        :usage-text="usageLine"
        :tokens="tokens"
        :component-props="componentProps"
      />
    </div>
  </section>
</template>
