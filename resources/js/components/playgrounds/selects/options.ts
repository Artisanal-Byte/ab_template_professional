import type { SelectOption } from '@/types';

export const createSelectOptions = (): SelectOption[] => [
  {
    label: 'Product',
    value: 'product',
    group: 'Teams',
    description: 'Roadmap planning',
  },
  {
    label: 'Marketing',
    value: 'marketing',
    group: 'Teams',
    description: 'Campaign planning',
  },
  {
    label: 'Finance',
    value: 'finance',
    group: 'Teams',
    description: 'Monthly reporting',
  },
  {
    label: 'Onboarding',
    value: 'onboarding',
    group: 'Projects',
    description: 'New starter flows',
  },
  {
    label: 'Release',
    value: 'release',
    group: 'Projects',
    description: 'Version milestones',
  },
  {
    label: 'Support',
    value: 'support',
    group: 'Projects',
    description: 'Customer follow-ups',
  },
];
