<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import { OTPInput } from 'vue-input-otp';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    length: {
        type: Number,
        default: 0,
    },
    otpLength: {
        type: Number,
        default: 6,
    },
    separatorAt: {
        type: Number,
        default: 0,
    },
});

const model = defineModel({
    type: String,
    default: '',
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

const containerClass = computed(() =>
    cn('flex items-center gap-2', attrsSplit.value.className),
);

const resolvedLength = computed(() =>
    props.otpLength || props.length || 6,
);

const boundAttrs = computed(() => attrsSplit.value.rest);

const slotClass =
    'relative flex h-10 w-10 items-center justify-center rounded-md border border-border bg-background text-sm text-foreground shadow-sm transition-all focus-within:ring-2 focus-within:ring-focus-ring';
const activeClass =
    'ring-2 ring-focus-ring border-primary';
const caretClass = 'absolute inset-0 flex items-center justify-center';
</script>

<template>
    <OTPInput
        v-model="model"
        v-bind="boundAttrs"
        :maxlength="resolvedLength"
        class="disabled:cursor-not-allowed"
        :container-class="containerClass"
    >
        <template #default="{ slots }">
            <div class="flex items-center gap-2">
                <template v-for="(slot, index) in slots" :key="index">
                    <div
                        :data-active="slot.isActive"
                        :class="[
                            slotClass,
                            slot.isActive ? activeClass : '',
                        ]"
                    >
                        {{ slot.char }}
                        <div v-if="slot.hasFakeCaret" :class="caretClass">
                            <div class="h-4 w-px animate-caret-blink bg-foreground" />
                        </div>
                    </div>
                    <div
                        v-if="props.separatorAt && index + 1 === props.separatorAt"
                        class="text-foreground-disabled"
                        role="separator"
                    >
                        -
                    </div>
                </template>
            </div>
        </template>
    </OTPInput>
</template>

