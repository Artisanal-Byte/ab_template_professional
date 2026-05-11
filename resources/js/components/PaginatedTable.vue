<script setup lang="ts">
import { computed, ref } from 'vue';
import { watchDebounced } from '@vueuse/core';
import { cn } from '@/lib/utils';
import {
    tableBaseClasses,
    tableHeaderRowClasses,
    tableHeaderCellClasses,
    tableRowClasses,
    tableCellClasses,
} from '@/lib/uiClass';
import Icon from '@/components/Icon.vue';
import Select from '@/components/ui/Select.vue';
import Label from '@/components/ui/Label.vue';
import type { TableColumn, SortState, SelectOption } from '@/types';
import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';

defineOptions({ inheritAttrs: false });

const props = withDefaults(
    defineProps<{
        columns: TableColumn[];
        data: Record<string, unknown>[];
        loading?: boolean;
        searchable?: boolean;
        showPerPage?: boolean;
        perPageOptions?: number[];
        defaultPerPage?: number;
        showJumpToPage?: boolean;
        showTopControls?: boolean;
        searchDebounce?: number;
        borders?: 'horizontal' | 'full';
        striped?: boolean;
        hoverable?: boolean;
        emptyMessage?: string;
        sortable?: boolean;
        defaultSort?: { key: string; direction: 'asc' | 'desc' };
    }>(),
    {
        loading: false,
        searchable: true,
        showPerPage: true,
        perPageOptions: () => [10, 25, 50, 100],
        defaultPerPage: 10,
        showJumpToPage: false,
        showTopControls: true,
        searchDebounce: 300,
        borders: 'horizontal' as const,
        striped: false,
        hoverable: true,
        emptyMessage: 'No records found.',
        sortable: true,
        defaultSort: undefined,
    },
);

const emit = defineEmits<{
    'update:search': [value: string];
    'update:sort': [value: SortState];
    'update:page': [value: number];
    'update:perPage': [value: number];
}>();

// ── Internal state ──
const searchQuery = ref('');
const debouncedSearchQuery = ref('');
const sortState = ref<SortState>({
    key: props.defaultSort?.key ?? null,
    direction: props.defaultSort?.direction ?? 'asc',
});
const currentPage = ref(1);
const perPage = ref(props.defaultPerPage);

// ── Computed data pipeline ──
const skeletonRows = computed(() => Math.min(perPage.value, 5));

const searchableColumnKeys = computed(() =>
    props.columns
        .filter((col) => col.searchable !== false)
        .map((col) => col.key),
);

const filteredData = computed(() => {
    if (!debouncedSearchQuery.value.trim()) {
        return props.data;
    }

    const q = debouncedSearchQuery.value.trim().toLowerCase();
    return props.data.filter((row) =>
        searchableColumnKeys.value.some((key) => {
            const val = row[key];
            return val != null && String(val).toLowerCase().includes(q);
        }),
    );
});

const sortedData = computed(() => {
    const { key, direction } = sortState.value;

    if (!key) {
        return filteredData.value;
    }

    return [...filteredData.value].sort((a, b) => {
        const aVal = a[key];
        const bVal = b[key];

        if (aVal == null && bVal == null) return 0;
        if (aVal == null) return 1;
        if (bVal == null) return -1;

        let cmp = 0;

        if (typeof aVal === 'number' && typeof bVal === 'number') {
            cmp = aVal - bVal;
        } else if (aVal instanceof Date && bVal instanceof Date) {
            cmp = aVal.getTime() - bVal.getTime();
        } else {
            cmp = String(aVal).localeCompare(String(bVal));
        }

        return direction === 'asc' ? cmp : -cmp;
    });
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(sortedData.value.length / perPage.value)),
);

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return sortedData.value.slice(start, start + perPage.value);
});

const startRecord = computed(() =>
    sortedData.value.length === 0
        ? 0
        : (currentPage.value - 1) * perPage.value + 1,
);

const endRecord = computed(() =>
    Math.min(currentPage.value * perPage.value, sortedData.value.length),
);

const jumpToPageInput = ref<number | string>('');

const perPageSelectOptions = computed<SelectOption[]>(() =>
    props.perPageOptions.map((n) => ({ value: n, label: String(n) })),
);

const hasData = computed(() => filteredData.value.length > 0);

// ── Methods ──
function toggleSort(key: string): void {
    if (sortState.value.key === key) {
        if (sortState.value.direction === 'asc') {
            sortState.value = { key, direction: 'desc' };
        } else {
            sortState.value = { key: null, direction: 'asc' };
        }
    } else {
        sortState.value = { key, direction: 'asc' };
    }
    currentPage.value = 1;
    emit('update:sort', sortState.value);
}

function goToPage(page: number): void {
    const p = Math.max(1, Math.min(page, totalPages.value));
    if (p !== currentPage.value) {
        currentPage.value = p;
        emit('update:page', p);
    }
}

function jumpToPage(): void {
    const page = Number(jumpToPageInput.value);
    if (!isNaN(page) && page >= 1 && page <= totalPages.value) {
        goToPage(page);
    }
    jumpToPageInput.value = '';
}

function handlePerPageChange(newPerPage: number): void {
    perPage.value = newPerPage;
    currentPage.value = 1;
    emit('update:perPage', newPerPage);
}

function handleSearchInput(value: string | number | null): void {
    searchQuery.value = String(value ?? '');
}

function clearSearch(): void {
    searchQuery.value = '';
    debouncedSearchQuery.value = '';
    currentPage.value = 1;
    emit('update:search', '');
}

watchDebounced(
    searchQuery,
    (val) => {
        debouncedSearchQuery.value = val;
        currentPage.value = 1;
        emit('update:search', val);
    },
    { debounce: props.searchDebounce },
);

function formatCellValue(col: TableColumn, row: Record<string, unknown>): string {
    if (col.format) {
        return col.format(row[col.key], row);
    }
    return String(row[col.key] ?? '');
}

function getSortIconMeta(col: TableColumn): { name: string; className: string } {
    if (sortState.value.key !== col.key) {
        return { name: 'lucide:chevrons-up-down', className: 'opacity-40' };
    }
    return {
        name: sortState.value.direction === 'asc' ? 'lucide:chevron-up' : 'lucide:chevron-down',
        className: 'opacity-100',
    };
}
</script>

<template>
    <div class="space-y-4">
        <!-- ── Top Controls (toggleable) ── -->
        <div
            v-if="showTopControls && !loading"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <!-- Search -->
            <div v-if="searchable" class="relative max-w-sm flex-1">
                <Label for="table-search" class="sr-only">Search records</Label>
                <Icon
                    name="lucide:search"
                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-foreground-faint pointer-events-none z-10"
                />
                <Input
                    id="table-search"
                    :model-value="searchQuery"
                    placeholder="Search..."
                    class="pl-9 pr-9"
                    @update:model-value="handleSearchInput"
                />
                <Button
                    v-if="searchQuery"
                    variant="ghost"
                    size="icon"
                    class="absolute right-1 top-1/2 -translate-y-1/2 h-8 w-8"
                    @click="clearSearch"
                >
                    <Icon name="lucide:x" class="h-3.5 w-3.5" />
                </Button>
            </div>

            <!-- Right side: prev/next + per-page -->
            <div v-if="hasData" class="flex items-center gap-3">
                <div v-if="showPerPage" class="flex items-center gap-2 text-sm text-foreground-subtle">
                    <span>Rows:</span>
                    <Select
                        :model-value="perPage"
                        :options="perPageSelectOptions"
                        width="xs"
                        @update:model-value="handlePerPageChange"
                    />
                </div>

                <div class="flex items-center gap-1">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="currentPage <= 1"
                        @click="goToPage(currentPage - 1)"
                    >
                        <Icon name="lucide:chevron-left" class="h-4 w-4" />
                        <span class="hidden sm:inline">Prev.</span>
                    </Button>
                    <span class="inline-flex min-w-8 items-center justify-center px-1 text-sm text-foreground font-medium">{{ currentPage }}</span>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="currentPage >= totalPages"
                        @click="goToPage(currentPage + 1)"
                    >
                        <span class="hidden sm:inline">Next</span>
                        <Icon name="lucide:chevron-right" class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>

        <!-- ── Table Wrapper ── -->
        <div class="relative overflow-auto rounded-md border border-border">
            <!-- Loading overlay -->
            <div
                v-if="loading"
                class="absolute inset-0 z-10 flex items-center justify-center bg-background/60"
            >
                <Spinner size="lg" />
            </div>

            <table :class="tableBaseClasses(borders)">
                <thead>
                    <tr :class="tableHeaderRowClasses(borders)">
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            :class="cn(tableHeaderCellClasses(borders), col.width, col.headerClassName)"
                        >
                            <span
                                v-if="col.sortable && sortable"
                                class="inline-flex cursor-pointer select-none items-center gap-1.5 hover:opacity-80 transition-opacity"
                                @click="toggleSort(col.key)"
                            >
                                <span>{{ col.label }}</span>
                                <Icon
                                    :name="getSortIconMeta(col).name"
                                    class="h-3.5 w-3.5 transition-opacity"
                                    :class="getSortIconMeta(col).className"
                                />
                            </span>
                            <span v-else>{{ col.label }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Skeleton rows -->
                    <template v-if="loading">
                    <tr v-for="n in skeletonRows" :key="'skeleton-' + n">
                        <td v-for="col in columns" :key="col.key" :class="tableCellClasses(borders)">
                            <Skeleton class="h-4 w-full" />
                        </td>
                    </tr>
                    </template>

                    <!-- Data rows -->
                    <tr
                        v-else
                        v-for="(row, idx) in paginatedData"
                        :key="idx"
                        :class="tableRowClasses(borders, striped, hoverable, idx)"
                    >
                        <td
                            v-for="col in columns"
                            :key="col.key"
                            :class="cn(tableCellClasses(borders), col.className)"
                        >
                            <slot
                                :name="`cell-${col.key}`"
                                :value="row[col.key]"
                                :row="row"
                            >
                                {{ formatCellValue(col, row) }}
                            </slot>
                        </td>
                    </tr>

                    <!-- Empty state -->
                    <tr v-if="!loading && paginatedData.length === 0">
                        <td
                            :colspan="columns.length"
                            class="px-4 py-8 text-center text-sm text-foreground-subtle"
                        >
                            <slot name="empty">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon name="lucide:table" class="h-8 w-8 text-foreground-faint" />
                                    <p>{{ emptyMessage }}</p>
                                </div>
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ── Bottom Pagination Bar (always visible) ── -->
        <div
            v-if="!loading && hasData"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <!-- Left: record info + per-page -->
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-foreground-subtle">
                <span>
                    Showing <strong class="text-foreground">{{ startRecord }}</strong>
                    – <strong class="text-foreground">{{ endRecord }}</strong>
                    of <strong class="text-foreground">{{ filteredData.length }}</strong> records
                </span>

                <div v-if="showPerPage" class="flex items-center gap-2">
                    <span>Rows:</span>
                    <Select
                        :model-value="perPage"
                        :options="perPageSelectOptions"
                        width="xs"
                        @update:model-value="handlePerPageChange"
                    />
                </div>
            </div>

            <!-- Right: prev/next + jump-to-page -->
            <div class="flex items-center gap-1">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage <= 1"
                    @click="goToPage(currentPage - 1)"
                >
                    <Icon name="lucide:chevron-left" class="h-4 w-4" />
                    <span class="hidden sm:inline">Prev.</span>
                </Button>
                <span class="inline-flex min-w-8 items-center justify-center px-1 text-sm text-foreground font-medium">{{ currentPage }}</span>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage >= totalPages"
                    @click="goToPage(currentPage + 1)"
                >
                    <span class="hidden sm:inline">Next</span>
                    <Icon name="lucide:chevron-right" class="h-4 w-4" />
                </Button>

                <!-- Jump to page -->
                <div v-if="showJumpToPage" class="ml-2 flex items-center gap-1.5">
                    <span class="text-sm text-foreground-subtle whitespace-nowrap">Go to:</span>
                    <Input
                        v-model="jumpToPageInput"
                        type="number"
                        :min="1"
                        :max="totalPages"
                        placeholder="#"
                        size="sm"
                        class="w-16 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        @keyup.enter="jumpToPage"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
