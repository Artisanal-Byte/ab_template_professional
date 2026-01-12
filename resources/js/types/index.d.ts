import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    roles?: string[];
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
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    currentTenant?: {
        id: string;
        name: string;
        slug: string;
    } | null;
    tenantMemberships?: Array<{
        tenant_id: string;
        tenant_name: string | null;
        membership_role: string;
        status: string;
    }>;
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
