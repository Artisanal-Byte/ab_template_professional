import { dashboard } from '@/routes';
import { system as designSystem } from '@/routes/design';
import type { NavItem } from '@/types';

export const mainNavItems = (): NavItem[] => [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: 'lucide:layout-grid',
    },
];

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
