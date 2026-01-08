<script setup lang="ts">
import { computed, onBeforeUnmount, ref, useAttrs, watch } from 'vue';
import {
    PopoverContent,
    PopoverPortal,
    PopoverRoot,
    PopoverTrigger,
} from 'reka-ui';
import { CalendarDays, Clock } from 'lucide-vue-next';
import { cn } from '@/lib/utils';
import { splitAttrs } from '@/lib/attrs';
import { inputBase } from '@/lib/uiClass';
import FieldError from '@/components/ui/FieldError.vue';
import {
    applyDateMask,
    applyDateTimeMask,
    applyTimeMask,
    buildCalendarCells,
    buildMinuteOptions,
    formatDate,
    formatDateTime,
    formatIsoDate,
    formatIsoDateTime,
    formatMonthLabel,
    getPolarStyle,
    getTimeParts,
    normalizeMinute,
    parseDate,
    parseIsoDate,
    parseTime,
} from '@/composables/DateTimePicker';

const CLOCK_RADIUS = 78;
const CLOCK_HAND_LENGTH = CLOCK_RADIUS;
const CLOCK_SIZE_CLASS = 'h-52 w-52';

const props = defineProps({
    mode: {
        type: String,
        default: 'date',
    },
    format: {
        type: String,
        default: 'DD-MM-YYYY',
    },
    timeFormat: {
        type: String,
        default: 'HH:mm',
    },
    timeStep: {
        type: Number,
        default: 15,
    },
    size: {
        type: String,
        default: 'md',
    },
    placeholder: {
        type: String,
        default: '',
    },
    error: {
        type: [String, Array, Object],
        default: '',
    },
});

const model = defineModel({
    default: '',
});

const attrs = useAttrs() as Record<string, unknown>;
const attrsSplit = computed(() => splitAttrs(attrs));

// Displayed input value (formatted/masked display text).
const inputValue = ref('');
// Selected calendar date (null if only time is chosen).
const selectedDate = ref<Date | null>(null);
// Selected time string in HH:mm.
const selectedTime = ref('00:00');
// Calendar view month anchor.
const viewDate = ref(new Date());
// Time picker unit state.
const activeTimeUnit = ref<'hour' | 'minute'>('hour');
// Popover and interaction state.
const open = ref(false);
const isDragging = ref(false);
const isSwitching = ref(false);
const inputRef = ref<HTMLInputElement | null>(null);
const clockRef = ref<HTMLDivElement | null>(null);
let switchTimer: ReturnType<typeof setTimeout> | null = null;
const hasTimeSelection = ref(false);

const modeConfig = computed(() => ({
    isDate: props.mode === 'date',
    isTime: props.mode === 'time',
    isDateTime: props.mode === 'datetime',
    hasDate: props.mode !== 'time',
    hasTime: props.mode !== 'date',
}));

const inputClass = computed(() =>
    inputBase(props.size, cn('pr-10', attrsSplit.value.className)),
);

const boundAttrs = computed(() => attrsSplit.value.rest);

const placeholderText = computed(() => {
    if (props.placeholder) {
        return props.placeholder;
    }
    if (modeConfig.value.isTime) {
        return props.timeFormat;
    }
    if (modeConfig.value.isDateTime) {
        return `${props.format} ${props.timeFormat}`;
    }
    return props.format;
});

const monthLabel = computed(() => formatMonthLabel(viewDate.value));
const calendarCells = computed(() => buildCalendarCells(viewDate.value));

const minuteOptions = computed(() => buildMinuteOptions(props.timeStep));
const minuteIndex = computed(() => {
    const currentMinute = getTimeParts(selectedTime.value).minute;
    const index = minuteOptions.value.indexOf(currentMinute);
    return index === -1 ? 0 : index;
});

const hourMarkers = computed(() =>
    Array.from({ length: 24 }, (_, index) => ({
        value: index,
        label: String(index).padStart(2, '0'),
        style: getPolarStyle(index, 24, CLOCK_RADIUS),
    })),
);

const minuteMarkers = computed(() =>
    minuteOptions.value.map((minute, index) => ({
        value: minute,
        label: String(minute).padStart(2, '0'),
        style: getPolarStyle(index, minuteOptions.value.length, CLOCK_RADIUS),
    })),
);

const handAngle = computed(() => {
    if (activeTimeUnit.value === 'hour') {
        return (getTimeParts(selectedTime.value).hour * 15 + 180) % 360;
    }
    return (
        (minuteIndex.value / minuteOptions.value.length) * 360 +
        180
    ) % 360;
});

const handStyle = computed(() => ({
    transform: `translate(-50%, 0) rotate(${handAngle.value}deg)`,
    height: `${CLOCK_HAND_LENGTH}px`,
}));

const clockClass = computed(() =>
    cn(
        'relative select-none rounded-full border border-border bg-secondary-soft transition-all duration-300 ease-out',
        CLOCK_SIZE_CLASS,
        isSwitching.value ? 'scale-95 opacity-80' : 'scale-100 opacity-100',
    ),
);

const navButtonClass =
    'rounded-md p-2 text-foreground-faint hover:bg-secondary-active hover:text-foreground';

const dayButtonClass = (active: boolean) =>
    cn(
        'flex h-8 w-8 items-center justify-center rounded-md text-sm text-foreground-subtle hover:bg-secondary-active hover:text-foreground',
        active ? 'bg-primary text-primary-foreground hover:bg-primary90' : '',
    );

const timeChipClass = (active: boolean) =>
    cn(
        'rounded-md px-2 py-1',
        active ? 'bg-secondary-active text-foreground' : 'text-foreground-faint',
    );

const markerButtonClass = (active: boolean) =>
    cn(
        'absolute flex h-7 w-7 items-center justify-center rounded-full text-xs',
        active
            ? 'bg-primary text-primary-foreground'
            : 'text-foreground-subtle hover:bg-secondary-active hover:text-foreground',
    );

const timeLabel = computed(() =>
    activeTimeUnit.value === 'hour' ? 'Hours' : 'Minutes',
);

const icon = computed(() => (modeConfig.value.isTime ? Clock : CalendarDays));

const updateModelFromSelection = () => {
    if (modeConfig.value.isTime) {
        model.value = selectedTime.value;
        inputValue.value = selectedTime.value;
        return;
    }
    if (!selectedDate.value) {
        return;
    }
    if (modeConfig.value.isDateTime) {
        model.value = formatIsoDateTime(
            selectedDate.value,
            selectedTime.value,
        );
        inputValue.value = formatDateTime(
            selectedDate.value,
            selectedTime.value,
            props.format,
        );
        return;
    }
    model.value = formatIsoDate(selectedDate.value);
    inputValue.value = formatDate(selectedDate.value, props.format);
};

const setTimeParts = (hour: number, minute: number) => {
    const safeHour = Math.min(Math.max(hour, 0), 23);
    const safeMinute = Math.min(Math.max(minute, 0), 59);
    selectedTime.value = `${String(safeHour).padStart(2, '0')}:${String(
        normalizeMinute(safeMinute, props.timeStep),
    ).padStart(2, '0')}`;
    updateModelFromSelection();
};

const syncFromModel = (value: string) => {
    if (!value) {
        selectedDate.value = null;
        selectedTime.value = '00:00';
        inputValue.value = '';
        hasTimeSelection.value = false;
        return;
    }

    if (modeConfig.value.isTime) {
        const time = parseTime(value);
        if (time) {
            selectedTime.value = time;
            inputValue.value = time;
        }
        return;
    }

    const time = parseTime(value);
    let datePart = value;
    if (time) {
        datePart = value.replace(time, '').trim();
    }

    const date = parseIsoDate(datePart) || parseDate(datePart, props.format);
    if (date) {
        selectedDate.value = date;
        viewDate.value = new Date(date.getFullYear(), date.getMonth(), 1);
        if (modeConfig.value.isDateTime) {
            inputValue.value = formatDateTime(
                date,
                time || selectedTime.value,
                props.format,
            );
        } else {
            inputValue.value = formatDate(date, props.format);
        }
    }

    if (time && modeConfig.value.isDateTime) {
        selectedTime.value = time;
        hasTimeSelection.value = true;
    }
};

const commitInputValue = () => {
    if (!inputValue.value) {
        model.value = '';
        return;
    }

    if (modeConfig.value.isTime) {
        const time = parseTime(inputValue.value);
        if (time) {
            const { hour, minute } = getTimeParts(time);
            setTimeParts(hour, minute);
            inputValue.value = selectedTime.value;
        }
        return;
    }

    const time = parseTime(inputValue.value);
    const datePart = time
        ? inputValue.value.replace(time, '').trim()
        : inputValue.value;
    const date = parseDate(datePart, props.format);
    if (!date) {
        return;
    }

    selectedDate.value = date;
    viewDate.value = new Date(date.getFullYear(), date.getMonth(), 1);

    if (modeConfig.value.isDateTime) {
        if (time) {
            const { hour, minute } = getTimeParts(time);
            setTimeParts(hour, minute);
            hasTimeSelection.value = true;
        } else {
            updateModelFromSelection();
        }
        inputValue.value = formatDateTime(
            date,
            selectedTime.value,
            props.format,
        );
        return;
    }

    model.value = formatIsoDate(date);
    inputValue.value = formatDate(date, props.format);
};

const onInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (modeConfig.value.isTime) {
        inputValue.value = applyTimeMask(target.value);
        target.value = inputValue.value;
        return;
    }
    if (modeConfig.value.isDateTime) {
        inputValue.value = applyDateTimeMask(target.value, props.format);
        target.value = inputValue.value;
        return;
    }
    inputValue.value = applyDateMask(target.value, props.format);
    target.value = inputValue.value;
};

const onBlur = () => {
    commitInputValue();
};

const shouldCloseAfterDate = () => {
    if (!modeConfig.value.isDateTime) {
        return true;
    }
    return hasTimeSelection.value;
};

const shouldCloseAfterTime = () => {
    if (modeConfig.value.isTime) {
        return true;
    }
    if (modeConfig.value.isDateTime) {
        return Boolean(selectedDate.value);
    }
    return false;
};

const selectDate = (date: Date) => {
    selectedDate.value = date;
    updateModelFromSelection();
    if (shouldCloseAfterDate()) {
        open.value = false;
    }
};

const goPrevMonth = () => {
    viewDate.value = new Date(
        viewDate.value.getFullYear(),
        viewDate.value.getMonth() - 1,
        1,
    );
};

const goNextMonth = () => {
    viewDate.value = new Date(
        viewDate.value.getFullYear(),
        viewDate.value.getMonth() + 1,
        1,
    );
};

const setToday = () => {
    const today = new Date();
    viewDate.value = new Date(today.getFullYear(), today.getMonth(), 1);
    selectedDate.value = today;
    updateModelFromSelection();
    if (!modeConfig.value.isDateTime) {
        hasTimeSelection.value = false;
    }
};

const clearValue = () => {
    model.value = '';
    selectedDate.value = null;
    hasTimeSelection.value = false;
};

const selectHour = (hour: number) => {
    const { minute } = getTimeParts(selectedTime.value);
    setTimeParts(hour, minute);
    hasTimeSelection.value = true;
    if (activeTimeUnit.value !== 'minute') {
        isSwitching.value = true;
        if (switchTimer) {
            clearTimeout(switchTimer);
        }
        switchTimer = setTimeout(() => {
            activeTimeUnit.value = 'minute';
            isSwitching.value = false;
            switchTimer = null;
        }, 220);
    }
};

const selectMinute = (minute: number) => {
    const { hour } = getTimeParts(selectedTime.value);
    setTimeParts(hour, minute);
    hasTimeSelection.value = true;
    if (shouldCloseAfterTime()) {
        isDragging.value = false;
        open.value = false;
    }
};

const isHourSelected = (hour: number) =>
    getTimeParts(selectedTime.value).hour === hour;
const isMinuteSelected = (minute: number) =>
    getTimeParts(selectedTime.value).minute === minute;

const pointerToValue = (event: PointerEvent) => {
    const rect = clockRef.value?.getBoundingClientRect();
    if (!rect) {
        return null;
    }
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;
    const dx = event.clientX - centerX;
    const dy = event.clientY - centerY;
    const angle = (Math.atan2(dy, dx) * 180) / Math.PI + 90 + 360;
    const normalized = angle % 360;

    if (activeTimeUnit.value === 'hour') {
        const step = 360 / 24;
        const hour = Math.round(normalized / step) % 24;
        return { hour };
    }

    const step = 360 / minuteOptions.value.length;
    const index =
        Math.round(normalized / step) % minuteOptions.value.length;
    return { minute: minuteOptions.value[index] };
};

const handlePointer = (event: PointerEvent) => {
    const result = pointerToValue(event);
    if (!result) {
        return;
    }
    if (typeof result.hour === 'number') {
        selectHour(result.hour);
    } else if (typeof result.minute === 'number') {
        selectMinute(result.minute);
    }
};

const onPointerDown = (event: PointerEvent) => {
    isDragging.value = true;
    handlePointer(event);
};

const onPointerMove = (event: PointerEvent) => {
    if (!isDragging.value) {
        return;
    }
    handlePointer(event);
};

const onPointerUp = () => {
    isDragging.value = false;
};

watch(
    () => model.value,
    (value) => {
        syncFromModel(String(value ?? ''));
    },
    { immediate: true },
);

watch(
    () => open.value,
    (value) => {
        if (value) {
            if (modeConfig.value.hasTime) {
                activeTimeUnit.value = 'hour';
            }
            isSwitching.value = false;
            isDragging.value = false;
            window.addEventListener('pointermove', onPointerMove);
            window.addEventListener('pointerup', onPointerUp);
            requestAnimationFrame(() => {
                inputRef.value?.focus();
            });
            return;
        }
        isDragging.value = false;
        window.removeEventListener('pointermove', onPointerMove);
        window.removeEventListener('pointerup', onPointerUp);
    },
);

onBeforeUnmount(() => {
    if (switchTimer) {
        clearTimeout(switchTimer);
    }
    window.removeEventListener('pointermove', onPointerMove);
    window.removeEventListener('pointerup', onPointerUp);
});
</script>

<template>
    <div class="grid gap-1">
        <PopoverRoot v-model:open="open">
            <PopoverTrigger as-child>
                <div class="relative w-full">
                    <input
                        ref="inputRef"
                        v-bind="boundAttrs"
                        :value="inputValue"
                        type="text"
                        :placeholder="placeholderText"
                        :class="inputClass"
                        @input="onInput"
                        @blur="onBlur"
                    />
                    <component
                        :is="icon"
                        class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-foreground-faint"
                    />
                </div>
            </PopoverTrigger>
            <PopoverPortal>
                <PopoverContent
                    class="z-50 mt-2 w-[min(22rem,90vw)] rounded-lg border border-border bg-card p-4 text-sm text-card-foreground shadow-lg"
                >
                    <div v-if="modeConfig.hasDate" class="grid gap-3">
                        <div class="flex items-center justify-between">
                            <button
                                type="button"
                                :class="navButtonClass"
                                @click="goPrevMonth"
                            >
                                &lt;
                            </button>
                            <div class="text-sm font-semibold">{{ monthLabel }}</div>
                            <button
                                type="button"
                                :class="navButtonClass"
                                @click="goNextMonth"
                            >
                                &gt;
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-xs text-foreground-faint">
                            <span
                                v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']"
                                :key="day"
                                class="text-center"
                            >
                                {{ day }}
                            </span>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <button
                                v-for="(cell, index) in calendarCells"
                                :key="`day-${index}`"
                                type="button"
                                :class="dayButtonClass(Boolean(cell && selectedDate && cell.getTime() === selectedDate.getTime()))"
                                :disabled="!cell"
                                @click="cell && selectDate(cell)"
                            >
                                <span v-if="cell">{{ cell.getDate() }}</span>
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <button
                                type="button"
                                class="text-foreground-faint hover:text-foreground"
                                @click="clearValue"
                            >
                                Clear
                            </button>
                            <button
                                type="button"
                                class="text-foreground-faint hover:text-foreground"
                                @click="setToday"
                            >
                                Today
                            </button>
                        </div>
                    </div>

                    <div v-if="modeConfig.hasTime" class="mt-4 border-t border-border pt-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm font-semibold">
                                <button
                                    type="button"
                                    :class="timeChipClass(activeTimeUnit === 'hour')"
                                    @click="activeTimeUnit = 'hour'"
                                >
                                    {{ selectedTime.split(':')[0] }}
                                </button>
                                <span class="text-foreground-disabled">:</span>
                                <button
                                    type="button"
                                    :class="timeChipClass(activeTimeUnit === 'minute')"
                                    @click="activeTimeUnit = 'minute'"
                                >
                                    {{ selectedTime.split(':')[1] }}
                                </button>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-foreground-faint">
                                {{ timeLabel }}
                            </span>
                        </div>
                        <div class="mt-4 flex items-center justify-center">
                            <div
                                ref="clockRef"
                                :class="clockClass"
                                @pointerdown="onPointerDown"
                            >
                                <div
                                    class="absolute left-1/2 top-1/2 w-0.5 origin-top bg-primary-strong"
                                    :style="handStyle"
                                />
                                <div
                                    class="absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary"
                                />
                                <template v-if="activeTimeUnit === 'hour'">
                                    <button
                                        v-for="marker in hourMarkers"
                                        :key="`hour-${marker.value}`"
                                        type="button"
                                        :class="markerButtonClass(isHourSelected(marker.value))"
                                        :style="marker.style"
                                        @click="selectHour(marker.value)"
                                    >
                                        {{ marker.label }}
                                    </button>
                                </template>
                                <template v-else>
                                    <button
                                        v-for="marker in minuteMarkers"
                                        :key="`minute-${marker.value}`"
                                        type="button"
                                        :class="markerButtonClass(isMinuteSelected(marker.value))"
                                        :style="marker.style"
                                        @click="selectMinute(marker.value)"
                                    >
                                        {{ marker.label }}
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </PopoverPortal>
        </PopoverRoot>
        <FieldError :error="props.error" />
    </div>
</template>


