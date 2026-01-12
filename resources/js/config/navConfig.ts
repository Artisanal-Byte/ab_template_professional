import {
    BookOpen,
    Building2,
    LayoutGrid,
    ShieldCheck,
    Users,
} from 'lucide-vue-next';
import { dashboard as adminDashboard } from '@/routes/admin';
import { dashboard as platformDashboard } from '@/routes/platform';
import { dashboard as tenantDashboard } from '@/routes/tenant';
import { index as adminUsersIndex } from '@/routes/platform/admin-users';
import { index as tenantsIndex } from '@/routes/admin/tenants';
import { index as tenantStaffIndex } from '@/routes/tenant/staff';
import { index as tenantRolesIndex } from '@/routes/tenant/roles';
import { index as tenantPermissionsIndex } from '@/routes/tenant/permissions';
import { system as designSystem } from '@/routes/design';
import type { NavItem } from '@/types';

export const mainNavItems = (
    roles: string[] = [],
    tenantRole: string | null = null,
): NavItem[] => {
    if (roles.includes('web_admin')) {
        return [
            {
                title: 'Platform',
                href: platformDashboard(),
                icon: LayoutGrid,
            },
            {
                title: 'Admin Users',
                href: adminUsersIndex(),
                icon: Users,
            },
        ];
    }

    if (roles.includes('admin')) {
        return [
            {
                title: 'Admin',
                href: adminDashboard(),
                icon: LayoutGrid,
            },
            {
                title: 'Organizations',
                href: tenantsIndex(),
                icon: Building2,
            },
        ];
    }

    if (tenantRole !== 'owner') {
        return [];
    }

    return [
        {
            title: 'Tenant',
            href: tenantDashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Staff',
            href: tenantStaffIndex(),
            icon: Users,
        },
        {
            title: 'Roles',
            href: tenantRolesIndex(),
            icon: ShieldCheck,
        },
        {
            title: 'Permissions',
            href: tenantPermissionsIndex(),
            icon: ShieldCheck,
        },
    ];
};

export const externalNavItems = (): NavItem[] => [
    ...(import.meta.env.PROD
        ? []
        : [
              {
                  title: 'Design System',
                  href: designSystem(),
                  icon: BookOpen,
              },
          ]),
];
