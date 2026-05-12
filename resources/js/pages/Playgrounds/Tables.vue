<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Card from '@/components/ui/Card.vue';
import PaginatedTable from '@/components/PaginatedTable.vue';
import Badge from '@/components/ui/Badge.vue';
import type { TableColumn, PaginationMeta, SelectOption } from '@/types';

// ── Column definitions ──
const columns: TableColumn[] = [
    {
        key: 'id',
        label: 'ID',
        sortable: true,
        width: 'w-16',
        className: 'text-foreground-faint',
    },
    {
        key: 'name',
        label: 'Name',
        sortable: true,
        searchable: true,
    },
    {
        key: 'email',
        label: 'Email',
        sortable: true,
        searchable: true,
        className: 'text-foreground-subtle',
    },
    {
        key: 'role',
        label: 'Role',
        sortable: true,
        searchable: true,
        width: 'w-28',
    },
    {
        key: 'status',
        label: 'Status',
        sortable: true,
        width: 'w-28',
    },
    {
        key: 'created_at',
        label: 'Created',
        sortable: true,
        width: 'w-32',
        className: 'text-foreground-subtle',
    },
    {
        key: 'revenue',
        label: 'Revenue',
        sortable: true,
        width: 'w-28',
        className: 'text-right font-mono',
        headerClassName: 'text-right',
        format: (value: unknown) => {
            const num = value as number;
            return `$${num.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        },
    },
];

// ── Server-driven state ──
const paginationMeta = ref<PaginationMeta>({
    currentPage: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
    from: null,
    to: null,
    previousPageUrl: null,
    nextPageUrl: null,
});
const tableData = ref<Record<string, unknown>[]>([]);
const searchQuery = ref('');
const searchField = ref('');
const sortField = ref('');
const sortDirection = ref<'asc' | 'desc'>('asc');
const loading = ref(false);
const tableRef = ref<InstanceType<typeof PaginatedTable> | null>(null);

// ── Props from controller ──
const props = defineProps<{
    serverData?: Record<string, unknown>[];
    paginationMeta?: PaginationMeta;
    searchFieldOptions?: SelectOption[];
    sortFieldOptions?: SelectOption[];
    queryParams?: {
        search?: string;
        search_field?: string;
        sort_field?: string;
        sort_direction?: string;
    };
}>();

// ── Initialize from server props ──
function initializeFromServer(): void {
    tableData.value = props.serverData ?? [];
    paginationMeta.value = props.paginationMeta ?? paginationMeta.value;
    if (props.queryParams) {
        searchQuery.value = props.queryParams.search ?? '';
        searchField.value = props.queryParams.search_field ?? '';
        sortField.value = props.queryParams.sort_field ?? '';
        sortDirection.value = (props.queryParams.sort_direction as 'asc' | 'desc') ?? 'asc';
    }
}

initializeFromServer();

// ── Fetch function (parent handles API) ──
function fetchTableData(): void {
    loading.value = true;
    router.get(
        '/playground/tables',
        {
            page: paginationMeta.value.currentPage,
            per_page: paginationMeta.value.perPage,
            search: searchQuery.value,
            search_field: searchField.value,
            sort_field: sortField.value,
            sort_direction: sortDirection.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                            const serverProps = page.props as { serverData?: Record<string, unknown>[]; paginationMeta?: PaginationMeta };
                            tableData.value = serverProps.serverData ?? [];
                            paginationMeta.value = serverProps.paginationMeta ?? paginationMeta.value;
                loading.value = false;
            },
        },
    );
}

// ── Event handlers ──
function onPageChange(page: number): void {
    paginationMeta.value.currentPage = page;
    fetchTableData();
}

function onPerPageChange(perPage: number): void {
    paginationMeta.value.perPage = perPage;
    paginationMeta.value.currentPage = 1;
    fetchTableData();
}

function onSearchSubmit(payload: { field: string; query: string }): void {
    searchField.value = payload.field;
    searchQuery.value = payload.query;
    paginationMeta.value.currentPage = 1;
    fetchTableData();
}

function onSortChange(payload: { field: string; direction: 'asc' | 'desc' }): void {
    sortField.value = payload.field;
    sortDirection.value = payload.direction;
    fetchTableData();
}
</script>

<template>
    <section class="grid gap-6">
        <div class="grid gap-2">
            <h2 class="text-2xl font-semibold">Paginated Table (Server Mode)</h2>
            <p class="text-sm text-foreground-subtle">
                Server-side pagination, search, and sorting demo. All state is driven via props and emits.
            </p>
        </div>

        <Card>
            <PaginatedTable ref="tableRef"
                :columns="columns"
                :data="tableData"
                :pagination-meta="paginationMeta"
                :search-query-value="searchQuery"
                :search-field-value="searchField"
                :search-field-options="props.searchFieldOptions ?? []"
                :sort-field-value="sortField"
                :sort-direction-value="sortDirection"
                :sort-field-options="props.sortFieldOptions ?? []"
                :loading="loading"
                :show-jump-to-page="true"
                :borders="'horizontal'"
                :hoverable="true"
                :striped="false"
                @update:search-query-value="(val: string) => { searchQuery = val; }"
                @update:search-field-value="(val: string) => { searchField = val; }"
                @search-submit="onSearchSubmit"
                @update:sort-field-value="(val: string) => { sortField = val; }"
                @update:sort-direction-value="(val: 'asc' | 'desc') => { sortDirection = val; }"
                @sort-change="onSortChange"
                @page-change="onPageChange"
                @per-page-change="onPerPageChange"
            >
                <template #cell-status="{ value }">
                    <Badge
                        :variant="
                            value === 'active' ? 'success'
                                : value === 'inactive' ? 'neutral'
                                : 'warning'
                        "
                    >
                        {{ value }}
                    </Badge>
                </template>
            </PaginatedTable>
        </Card>
    </section>
</template>

