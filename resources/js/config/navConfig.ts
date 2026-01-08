import { BookOpen, LayoutGrid } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { system as designSystem } from '@/routes/design';
import type { NavItem } from '@/types';

export const mainNavItems = (): NavItem[] => [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

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
