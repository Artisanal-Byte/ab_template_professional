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
                icon: 'lucide:layout-grid',
            },
            {
                title: 'Admin Users',
                href: adminUsersIndex(),
                icon: 'lucide:users',
            },
        ];
    }

    if (roles.includes('admin')) {
        return [
            {
                title: 'Admin',
                href: adminDashboard(),
                icon: 'lucide:layout-grid',
            },
            {
                title: 'Organizations',
                href: tenantsIndex(),
                icon: 'lucide:building-2',
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
            icon: 'lucide:layout-grid',
        },
        {
            title: 'Staff',
            href: tenantStaffIndex(),
            icon: 'lucide:users',
        },
        {
            title: 'Roles',
            href: tenantRolesIndex(),
            icon: 'lucide:shield-check',
        },
        {
            title: 'Permissions',
            href: tenantPermissionsIndex(),
            icon: 'lucide:shield-check',
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
                  icon: 'lucide:book-open',
              },
          ]),
];
