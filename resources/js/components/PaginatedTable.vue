<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
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
import type { TableColumn, SelectOption, PaginationMeta } from '@/types';
import Button from '@/components/ui/Button.vue';
import Input from '@/components/ui/Input.vue';
import Spinner from '@/components/ui/Spinner.vue';
import Skeleton from '@/components/ui/Skeleton.vue';

defineOptions({ inheritAttrs: false });

// ── Props ──
const props = withDefaults(
    defineProps<{
        columns: TableColumn[];
        data: Record<string, unknown>[];
        paginationMeta?: PaginationMeta;
        searchQueryValue?: string;
        searchFieldValue?: string;
        searchFieldOptions?: SelectOption[];
        sortFieldValue?: string;
        sortDirectionValue?: 'asc' | 'desc';
        sortFieldOptions?: SelectOption[];
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
        paginationMeta: () => ({
            currentPage: 1,
            lastPage: 1,
            perPage: 10,
            total: 0,
            from: null,
            to: null,
            previousPageUrl: null,
            nextPageUrl: null,
        }),
        searchQueryValue: '',
        searchFieldValue: '',
        searchFieldOptions: () => [],
        sortFieldValue: '',
        sortDirectionValue: 'asc' as 'asc' | 'desc',
        sortFieldOptions: () => [],
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

// ── Emits ──
const emit = defineEmits<{
    'update:searchQueryValue': [value: string];
    'update:searchFieldValue': [value: string];
    searchSubmit: [payload: { field: string; query: string }];
    'update:sortFieldValue': [value: string];
    'update:sortDirectionValue': [value: 'asc' | 'desc'];
    sortChange: [payload: { field: string; direction: 'asc' | 'desc' }];
    pageChange: [value: number];
    perPageChange: [value: number];
}>();

// ── Internal state (only for UI controls, not data pipeline) ──
const localSearchQuery = ref(props.searchQueryValue);
const jumpToPageInput = ref<number | string>('');

// ── Computed derivations (from props only) ──
const meta = computed(() => props.paginationMeta);

// Whether pagination meta is explicitly provided by server
const hasServerMeta = computed(() => meta.value.total > 0);

// Current page: server meta or local default
const currentPage = computed(() => meta.value.currentPage ?? 1);

// Per-page: server meta or local default
const perPage = computed(() => meta.value.perPage ?? props.defaultPerPage);

// Total pages: fallback to client-side calculation when server meta absent
const totalPages = computed(() =>
    hasServerMeta.value
        ? meta.value.lastPage
        : Math.max(1, Math.ceil(props.data.length / perPage.value)),
);

// Total records: fallback to data.length when server meta absent
const totalRecords = computed(() =>
    hasServerMeta.value ? meta.value.total : props.data.length,
);

// Start record: fallback to client-side calc when server meta absent
const startRecord = computed(() => {
    if (hasServerMeta.value) return meta.value.from ?? 0;
    return props.data.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1;
});

// End record: fallback to client-side calc when server meta absent
const endRecord = computed(() => {
    if (hasServerMeta.value) return meta.value.to ?? 0;
    return Math.min(currentPage.value * perPage.value, props.data.length);
});

// Display data: slice locally only when no server meta and data exceeds perPage
const displayData = computed(() => {
    if (hasServerMeta.value) return props.data;
    if (props.data.length <= perPage.value) return props.data;
    const start = (currentPage.value - 1) * perPage.value;
    return props.data.slice(start, start + perPage.value);
});

const hasData = computed(() => props.data.length > 0);

const perPageSelectOptions = computed<SelectOption[]>(() =>
    props.perPageOptions.map((n) => ({ value: n, label: String(n) })),
);

const skeletonRows = computed(() => Math.min(perPage.value, 5));

// ── Search handlers ──
let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null;

function handleSearchInput(value: string | number | null): void {
    const val = String(value ?? '');
    localSearchQuery.value = val;

    // Emit debounced update for query text
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => {
        emit('update:searchQueryValue', val);
    }, props.searchDebounce);
}

function handleSearchFieldChange(value: string): void {
    emit('update:searchFieldValue', value);
    // Submit immediately on field change
    emit('searchSubmit', { field: value, query: localSearchQuery.value });
}

function handleSearchSubmit(): void {
    emit('searchSubmit', { field: props.searchFieldValue, query: localSearchQuery.value });
}

function clearSearch(): void {
    localSearchQuery.value = '';
    emit('update:searchQueryValue', '');
    emit('update:searchFieldValue', '');
    emit('searchSubmit', { field: '', query: '' });
}

// ── Sort handlers ──
function handleSortChange(colKey: string): void {
    if (props.sortFieldValue === colKey) {
        // Toggle direction
        const newDir = props.sortDirectionValue === 'asc' ? 'desc' : 'asc';
        emit('update:sortDirectionValue', newDir);
        emit('sortChange', { field: colKey, direction: newDir });
    } else {
        // New sort field, default to asc
        emit('update:sortFieldValue', colKey);
        emit('update:sortDirectionValue', 'asc' as const);
        emit('sortChange', { field: colKey, direction: 'asc' as const });
    }
}

function getSortIconMeta(col: TableColumn): { name: string; className: string } {
    if (props.sortFieldOptions.length > 0) {
        // Sort field selector mode: show indicator based on sortFieldValue prop
        if (props.sortFieldValue !== col.key) {
            return { name: 'lucide:chevrons-up-down', className: 'opacity-40' };
        }
        return {
            name: props.sortDirectionValue === 'asc' ? 'lucide:chevron-up' : 'lucide:chevron-down',
            className: 'opacity-100',
        };
    }
    // Column header sort mode: use defaultSort fallback
    const defaultKey = props.defaultSort?.key ?? null;
    const defaultDir = props.defaultSort?.direction ?? 'asc';
    if (defaultKey !== col.key) {
        return { name: 'lucide:chevrons-up-down', className: 'opacity-40' };
    }
    return {
        name: defaultDir === 'asc' ? 'lucide:chevron-up' : 'lucide:chevron-down',
        className: 'opacity-100',
    };
}

// ── Pagination handlers ──
function handlePageChange(delta: number): void {
    const newPage = currentPage.value + delta;
    if (newPage >= 1 && newPage <= totalPages.value) {
        emit('pageChange', newPage);
    }
}

function jumpToPage(): void {
    const page = Number(jumpToPageInput.value);
    if (!isNaN(page) && page >= 1 && page <= totalPages.value) {
        emit('pageChange', page);
    }
    jumpToPageInput.value = '';
}

function handlePerPageChange(newPerPage: number): void {
    emit('perPageChange', newPerPage);
}

// ── Public methods (exposed via ref) ──
function goToPage(page: number): void {
    handlePageChange(page - currentPage.value);
}

// Expose methods for parent to call directly via ref
defineExpose({
    goToPage,
});


// ── Cell formatting ──
function formatCellValue(col: TableColumn, row: Record<string, unknown>): string {
    if (col.format) {
        return col.format(row[col.key], row);
    }
    return String(row[col.key] ?? '');
}
</script>

<template>
    <div class="space-y-4">
        <!-- ── Search Bar (independent toggle via searchable prop) ── -->
        <div
            v-if="searchable && !loading"
            class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3"
        >
            <!-- Search field selector -->
            <Select
                v-if="searchFieldOptions.length > 0"
                :model-value="searchFieldValue"
                :options="searchFieldOptions"
                width="sm"
                class="w-full sm:w-40"
                @update:model-value="handleSearchFieldChange"
            />

            <!-- Search input -->
            <div class="relative flex-1 min-w-0">
                <Label for="table-search" class="sr-only">Search records</Label>
                <Icon
                    name="lucide:search"
                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-foreground-faint pointer-events-none z-10"
                />
                <Input
                    id="table-search"
                    :model-value="localSearchQuery"
                    placeholder="Search..."
                    class="pl-9 pr-9"
                    @update:model-value="handleSearchInput"
                    @keyup.enter="handleSearchSubmit"
                />
                <Button
                    v-if="localSearchQuery || searchQueryValue"
                    variant="ghost"
                    size="icon"
                    class="absolute right-1 top-1/2 -translate-y-1/2 h-8 w-8"
                    @click="clearSearch"
                >
                    <Icon name="lucide:x" class="h-3.5 w-3.5" />
                </Button>
            </div>
        </div>

        <!-- ── Top Controls: pagination + per-page (toggleable via showTopControls) ── -->
        <div
            v-if="showTopControls && !loading && hasData"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <!-- Left: sort selector + per-page -->
            <div class="flex items-center gap-3">
                <!-- Sort field selector -->
                <Select
                    v-if="sortFieldOptions.length > 0"
                    :model-value="sortFieldValue"
                    :options="sortFieldOptions"
                    width="sm"
                    class="w-36"
                    @update:model-value="(val: string) => {
                        emit('update:sortFieldValue', val);
                    }"
                />

                <div v-if="showPerPage" class="flex items-center gap-2 text-sm text-foreground-subtle">
                    <span>Rows:</span>
                    <Select
                        :model-value="perPage"
                        :options="perPageSelectOptions"
                        width="xs"
                        @update:model-value="handlePerPageChange"
                    />
                </div>
            </div>

            <!-- Right: prev/next pagination -->
            <div class="flex items-center gap-1">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage <= 1"
                    @click="handlePageChange(-1)"
                >
                    <Icon name="lucide:chevron-left" class="h-4 w-4" />
                    <span class="hidden sm:inline">Prev.</span>
                </Button>
                <span class="inline-flex min-w-8 items-center justify-center px-1 text-sm text-foreground font-medium">{{ currentPage }}</span>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage >= totalPages"
                    @click="handlePageChange(1)"
                >
                    <span class="hidden sm:inline">Next</span>
                    <Icon name="lucide:chevron-right" class="h-4 w-4" />
                </Button>
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
                                v-if="col.sortable && sortable && sortFieldOptions.length === 0"
                                class="inline-flex cursor-pointer select-none items-center gap-1.5 hover:opacity-80 transition-opacity"
                                @click="handleSortChange(col.key)"
                            >
                                <span>{{ col.label }}</span>
                                <Icon
                                    :name="getSortIconMeta(col).name"
                                    class="h-3.5 w-3.5 transition-opacity"
                                    :class="getSortIconMeta(col).className"
                                />
                            </span>
                            <span
                                v-else-if="col.sortable && sortable && sortFieldOptions.length > 0 && sortFieldValue === col.key"
                                class="inline-flex items-center gap-1.5"
                            >
                                <span>{{ col.label }}</span>
                                <Icon
                                    name="lucide:chevron-up"
                                    class="h-3.5 w-3.5 opacity-100"
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
                        v-for="(row, idx) in displayData"
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
                    <tr v-if="!loading && data.length === 0">
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

        <!-- ── Bottom Pagination Bar (always visible when has data) ── -->
        <div
            v-if="!loading && hasData"
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <!-- Left: record info + per-page -->
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-foreground-subtle">
                <span>
                    Showing <strong class="text-foreground">{{ startRecord }}</strong>
                    – <strong class="text-foreground">{{ endRecord }}</strong>
                    of <strong class="text-foreground">{{ totalRecords }}</strong> records
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
                    @click="handlePageChange(-1)"
                >
                    <Icon name="lucide:chevron-left" class="h-4 w-4" />
                    <span class="hidden sm:inline">Prev.</span>
                </Button>
                <span class="inline-flex min-w-8 items-center justify-center px-1 text-sm text-foreground font-medium">{{ currentPage }}</span>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage >= totalPages"
                    @click="handlePageChange(1)"
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
