import { type ClassValue } from 'clsx';
import { cn } from '@/lib/utils';

export const inputSizeClasses: Record<string, string> = {
    sm: 'h-8 px-2 text-sm',
    md: 'h-10 px-3 text-sm',
    lg: 'h-11 px-4 text-base',
};

export const inputBaseClasses =
    'w-full rounded-md border border-border bg-background px-3 py-2 text-sm text-foreground placeholder:text-foreground-faint transition-colors outline-none focus-visible:ring-2 focus-visible:ring-focus-ring disabled:cursor-not-allowed disabled:opacity-50';

export const inputBase = (size: string, extra?: ClassValue) =>
    cn(
        inputBaseClasses,
        inputSizeClasses[size] || inputSizeClasses.md,
        extra,
    );

export const labelBase = (extra?: ClassValue) =>
    cn('text-sm font-medium text-foreground leading-none', extra);

export const cardBase = (extra?: ClassValue) =>
    cn(
        'rounded-lg border border-card-border bg-card text-card-foreground shadow-sm',
        extra,
    );

export const buttonBaseClasses =
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors outline-none focus-visible:ring-2 focus-visible:ring-focus-ring disabled:pointer-events-none disabled:opacity-50';

export const buttonVariantClasses: Record<string, string> = {
    primary: 'bg-primary text-primary-foreground hover:bg-primary',
    secondary: 'bg-secondary text-foreground hover:bg-secondary-strong',
    outline:
        'border border-border bg-background text-foreground hover:bg-secondary-subtle',
    ghost: 'bg-transparent text-foreground hover:bg-secondary-subtle',
    success: 'bg-success text-foreground hover:bg-success',
    info: 'bg-info text-foreground hover:bg-info',
    warning: 'bg-warning text-foreground hover:bg-warning',
    destructive: 'bg-error text-foreground hover:bg-error',
    link: 'text-primary underline underline-offset-4 hover:opacity-90',
};

export const buttonSizeClasses: Record<string, string> = {
    xs: 'h-6 px-2 text-xs',
    sm: 'h-8 px-3',
    md: 'h-10 px-4',
    lg: 'h-11 px-6 text-base',
    xl: 'h-12 px-7 text-base',
    icon: 'h-10 w-10',
};

export const buttonBase = (
    variant: string,
    size: string,
    extra?: ClassValue,
) =>
    cn(
        buttonBaseClasses,
        buttonVariantClasses[variant] || buttonVariantClasses.primary,
        buttonSizeClasses[size] || buttonSizeClasses.md,
        extra,
    );

export const tableBaseClasses = (borders: 'horizontal' | 'full') =>
    cn(
        'w-full text-sm text-foreground',
        borders === 'full' ? 'border-collapse' : 'border-collapse',
    );

export const tableHeaderRowClasses = (borders: 'horizontal' | 'full') =>
    cn(
        'bg-foreground text-primary-foreground',
        borders === 'full' ? 'border border-border' : 'border-b border-border',
    );

export const tableHeaderCellClasses = (borders: 'horizontal' | 'full') =>
    cn(
        'h-12 px-4 text-left align-middle font-medium text-primary-foreground',
        borders === 'full' && 'border border-border',
    );

export const tableRowClasses = (
    borders: 'horizontal' | 'full',
    striped: boolean,
    hoverable: boolean,
    index: number,
) =>
    cn(
        borders === 'full' ? 'border border-border' : 'border-b border-border',
        striped && index % 2 === 0 && 'bg-secondary/30',
        hoverable && 'transition-colors hover:bg-secondary-soft',
    );

export const tableCellClasses = (borders: 'horizontal' | 'full') =>
    cn(
        'px-4 py-3 align-middle',
        borders === 'full' ? 'border border-border' : 'border-b border-border',
    );

