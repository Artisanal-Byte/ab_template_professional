import { InertiaLinkProps } from '@inertiajs/vue3';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface SelectOption {
    value?: string | number;
    label?: string;
    description?: string;
    group?: string;
    disabled?: boolean;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: string;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface TableColumn<T = Record<string, unknown>> {
    key: string;
    label: string;
    sortable?: boolean;
    searchable?: boolean;
    width?: string;
    className?: string;
    headerClassName?: string;
    format?: (value: unknown, row: T) => string;
}

export interface SortState {
	key: string | null;
	direction: 'asc' | 'desc';
}

export interface PaginationMeta {
	currentPage: number;
	lastPage: number;
	perPage: number;
	total: number;
	from: number | null;
	to: number | null;
	previousPageUrl: string | null;
	nextPageUrl: string | null;
}
