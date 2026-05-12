<script setup lang="ts">
import { ref, computed } from 'vue';
import Card from '@/components/ui/Card.vue';
import PaginatedTable from '@/components/PaginatedTable.vue';
import Badge from '@/components/ui/Badge.vue';
import Icon from '@/components/Icon.vue';
import Label from '@/components/ui/Label.vue';
import Input from '@/components/ui/Input.vue';
import RadioPillGroup from '@/components/RadioPillGroup.vue';
import PlaygroundMetaPanel from '@/components/playgrounds/PlaygroundMetaPanel.vue';
import { booleanOptions } from '@/components/playgrounds/options';
import { buildAttrList } from '@/components/playgrounds/buildAttrList';
import type { TableColumn } from '@/types';

// ── Mock data ──
const mockUsers: Record<string, unknown>[] = Array.from({ length: 47 }, (_, i) => {
    const roles = ['Admin', 'Editor', 'Viewer', 'Manager', 'Contributor'];
    const statuses = ['active', 'inactive', 'pending'];
    const names = [
        'Alice Johnson', 'Bob Smith', 'Charlie Brown', 'Diana Prince',
        'Edward Norton', 'Fiona Apple', 'George Lucas', 'Hannah Montana',
        'Ivan Petrov', 'Julia Roberts', 'Kevin Hart', 'Laura Croft',
        'Mike Tyson', 'Nancy Drew', 'Oscar Wilde', 'Patty Smith',
        'Quinn Fabray', 'Rachel Green', 'Steve Jobs', 'Tina Fey',
        'Uma Thurman', 'Victor Hugo', 'Wendy Darling', 'Xander Cage',
        'Yara Shahidi', 'Zack Morris', 'Amy Adams', 'Bruce Wayne',
        'Clark Kent', 'Diana Ross', 'Eve Adams', 'Frank Castle',
        'Gina Linetti', 'Harry Potter', 'Iris West', 'Jack Sparrow',
        'Kate Bishop', 'Leo Messi', 'Mona Lisa', 'Nick Fury',
        'Olivia Pope', 'Peter Pan', 'Queen Latifah', 'Ron Swanson',
        'Sarah Connor', 'Tom Hardy', 'Uma Thurman',
    ];

    return {
        id: i + 1,
        name: names[i % names.length],
        email: `${names[i % names.length].toLowerCase().replace(' ', '.')}@example.com`,
        role: roles[i % roles.length],
        status: statuses[i % statuses.length],
        created_at: new Date(2024, 0, i + 1).toISOString().split('T')[0],
        revenue: Math.round(Math.random() * 50000 * 100) / 100,
    };
});

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

// ── Border options ──
const borderOptions = [
    { label: 'Horizontal', value: 'horizontal' },
    { label: 'Full', value: 'full' },
];

// ── Controls state ──
const borders = ref('horizontal');
const striped = ref('false');
const hoverable = ref('true');
const sortable = ref('true');
const showTopControls = ref('true');

const isStriped = computed(() => striped.value === 'true');
const isHoverable = computed(() => hoverable.value === 'true');
const isSortable = computed(() => sortable.value === 'true');
const hasTopControls = computed(() => showTopControls.value === 'true');

const previewBorders = computed(() => borders.value as 'horizontal' | 'full');

// ── Client-side pagination state ──
const currentPage = ref(1);
const perPage = ref(10);

const totalPages = computed(() => Math.max(1, Math.ceil(mockUsers.length / perPage.value)));

const paginationMeta = computed(() => ({
    currentPage: currentPage.value,
    lastPage: totalPages.value,
    perPage: perPage.value,
    total: 0,
    from: null,
    to: null,
    previousPageUrl: null,
    nextPageUrl: null,
}));

function handlePageChange(page: number): void {
    currentPage.value = page;
}

function handlePerPageChange(newPerPage: number): void {
    perPage.value = newPerPage;
    currentPage.value = 1;
}

// ── Meta panel state ──
const detailsOpen = ref(false);

const tokens = [
    { token: '--foreground', role: 'Header background' },
    { token: '--primary-foreground', role: 'Header text' },
    { token: '--background', role: 'Table background / row default' },
    { token: '--border', role: 'Row/cell borders' },
    { token: '--secondary-soft', role: 'Row hover background' },
    { token: '--foreground-subtle', role: 'Empty state / meta text' },
];

const componentProps = [
    {
        name: 'borders',
        type: "'horizontal' | 'full'",
        values: ['horizontal (default)', 'full'],
        defaultValue: 'horizontal',
    },
    {
        name: 'striped',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'false',
    },
    {
        name: 'hoverable',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'true',
    },
    {
        name: 'sortable',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'true',
    },
    {
        name: 'searchable',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'true',
    },
    {
        name: 'showPerPage',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'true',
    },
    {
        name: 'showTopControls',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'true',
    },
    {
        name: 'showJumpToPage',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'false',
    },
    {
        name: 'loading',
        type: 'boolean',
        values: ['true', 'false'],
        defaultValue: 'false',
    },
    {
        name: 'defaultPerPage',
        type: 'number',
        values: ['10 (default)', '25', '50', '100'],
        defaultValue: '10',
    },
    {
        name: 'perPageOptions',
        type: 'number[]',
        values: ['[10, 25, 50, 100] (default)'],
        defaultValue: '[10, 25, 50, 100]',
    },
    {
        name: 'emptyMessage',
        type: 'string',
        values: ['any string'],
        defaultValue: '"No records found."',
    },
];

const buildImports = () => [
    "import PaginatedTable from '@/components/PaginatedTable.vue';",
    "import type { TableColumn } from '@/types';",
];

const buildTableAttrs = () =>
    buildAttrList([
        { when: borders.value !== 'horizontal', attr: `borders="${borders.value}"` },
        { when: isStriped.value, attr: 'striped' },
        { when: !isHoverable.value, attr: ':hoverable="false"' },
        { when: !isSortable.value, attr: ':sortable="false"' },
        { when: !hasTopControls.value, attr: ':show-top-controls="false"' },
    ]);

const importText = computed(() => buildImports().join('\n'));

const usageLine = computed(() => {
    const attrs = buildTableAttrs();
    const attrText = attrs.length ? ` ${attrs.join(' ')}` : '';
    return `const columns: TableColumn[] = [\n  { key: 'id', label: 'ID', sortable: true },\n  // ...\n];\n\n<PaginatedTable${attrText}\n  :columns="columns"\n  :data="data"\n/>`;
});
</script>

<template>
    <section class="grid gap-6">
        <div class="grid gap-2">
            <h2 class="text-2xl font-semibold">Paginated Table</h2>
            <p class="text-sm text-foreground-subtle">
                Configure sorting, search, pagination controls, and copy the generated snippet.
            </p>
        </div>

        <!-- ── Controls ── -->
        <div class="grid gap-4 md:grid-cols-5">
            <RadioPillGroup v-model="borders" name="table-borders" label="Borders" :options="borderOptions" />
            <RadioPillGroup v-model="striped" name="table-striped" label="Striped" :options="booleanOptions" />
            <RadioPillGroup v-model="hoverable" name="table-hoverable" label="Hoverable" :options="booleanOptions" />
            <RadioPillGroup v-model="sortable" name="table-sortable" label="Sortable" :options="booleanOptions" />
            <RadioPillGroup v-model="showTopControls" name="table-top-controls" label="Top controls" :options="booleanOptions" />
        </div>

        <!-- ── Preview ── -->
        <Card>
            <template #title>Preview</template>
            <PaginatedTable
                :columns="columns"
                :data="mockUsers"
                :pagination-meta="paginationMeta"
                :borders="previewBorders"
                :striped="isStriped"
                :hoverable="isHoverable"
                :sortable="isSortable"
                :show-top-controls="hasTopControls"
                :default-per-page="10"
                @page-change="handlePageChange"
                @per-page-change="handlePerPageChange"
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

        <PlaygroundMetaPanel
            v-model:open="detailsOpen"
            :import-text="importText"
            :usage-text="usageLine"
            :tokens="tokens"
            :component-props="componentProps"
        />
    </section>
</template>
