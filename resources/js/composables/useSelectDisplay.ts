import type { SelectOption } from '@/types';

export const getOptionValue = (option: SelectOption) =>
    option?.value ?? option?.label ?? '';

export const getOptionLabel = (option: SelectOption) =>
    option?.label ?? String(option?.value ?? '');

export const getOptionDescription = (option: SelectOption) =>
    option?.description ?? '';
