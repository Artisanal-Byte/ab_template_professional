import type { SelectOption } from '@/types';

export const createSelectOptions = (): SelectOption[] => [
  {
    label: 'Clinical Operations',
    value: 'clinical',
    group: 'Departments',
    description: 'Patient-facing workflows',
  },
  {
    label: 'Quality Assurance',
    value: 'qa',
    group: 'Departments',
    description: 'Audit preparation',
  },
  {
    label: 'Regulatory',
    value: 'regulatory',
    group: 'Departments',
    description: 'Regulatory submissions',
  },
  {
    label: 'Change Control',
    value: 'change',
    group: 'Workflows',
    description: 'Controlled changes',
  },
  {
    label: 'Deviation',
    value: 'deviation',
    group: 'Workflows',
    description: 'Incident tracking',
  },
  {
    label: 'CAPA',
    value: 'capa',
    group: 'Workflows',
    description: 'Corrective actions',
  },
];
