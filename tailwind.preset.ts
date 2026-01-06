import type { Config } from 'tailwindcss';

export default {
    theme: {
        extend: {
            colors: {
                border: 'var(--border)',
                background: 'var(--background)',
                foreground: 'var(--foreground)',
                primary: {
                    DEFAULT: 'var(--primary)',
                    foreground: 'var(--primary-foreground)',
                },
                card: {
                    DEFAULT: 'var(--card)',
                    foreground: 'var(--card-foreground)',
                },
                neutral: 'var(--neutral)',
                secondary: 'var(--secondary)',
                accent: 'var(--accent)',
                info: 'var(--info)',
                success: 'var(--success)',
                warning: 'var(--warning)',
                error: 'var(--error)',
            },
            borderRadius: {
                lg: 'var(--radius-lg)',
                md: 'var(--radius-md)',
                sm: 'var(--radius-sm)',
            },
        },
    },
} satisfies Omit<Config, 'content'>;
