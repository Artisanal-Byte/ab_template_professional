import { type ClassValue } from 'clsx';

export const splitAttrs = (attrs: Record<string, unknown>) => {
    const rest = { ...attrs };
    delete rest.class;
    return {
        className: attrs.class as ClassValue,
        rest,
    };
};
