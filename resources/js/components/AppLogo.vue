<script setup lang="ts">
import { computed } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps({
    label: {
        type: String,
        default: 'Laravel Starter Kit',
    },
    showText: {
        type: Boolean,
        default: true,
    },
    size: {
        type: String,
        default: 'md',
    },
    containerClass: {
        type: String,
        default: '',
    },
    iconClass: {
        type: String,
        default: '',
    },
    textClass: {
        type: String,
        default: '',
    },
});

const containerSizes: Record<string, string> = {
    sm: 'h-8 w-8',
    md: 'h-9 w-9',
    lg: 'h-10 w-10',
};

const iconSizes: Record<string, string> = {
    sm: 'h-4 w-4',
    md: 'h-5 w-5',
    lg: 'h-6 w-6',
};

const containerClasses = computed(() =>
    cn(
        'flex items-center justify-center rounded-md bg-primary text-primary-foreground',
        containerSizes[props.size] || containerSizes.md,
        props.containerClass,
    ),
);

const iconClasses = computed(() =>
    cn(iconSizes[props.size] || iconSizes.md, props.iconClass),
);
</script>

<template>
    <div :class="containerClasses">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 40 42"
            :class="iconClasses"
        >
            <path
                fill="currentColor"
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z"
            />
        </svg>
    </div>
    <div
        v-if="props.showText"
        :class="cn('ml-1 grid flex-1 text-left text-sm', props.textClass)"
    >
        <span class="mb-0.5 truncate leading-tight font-semibold">{{
            props.label
        }}</span>
    </div>
</template>
