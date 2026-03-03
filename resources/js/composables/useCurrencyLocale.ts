const supportedCurrencyLocales = [
    { locale: 'en-IN', currency: 'INR' },
    { locale: 'en-US', currency: 'USD' },
    { locale: 'en-GB', currency: 'GBP' },
    { locale: 'de-DE', currency: 'EUR' },
    { locale: 'fr-FR', currency: 'EUR' },
    { locale: 'ja-JP', currency: 'JPY' },
];

const normalizeLocale = (value: string) =>
    value
        .trim()
        .replace('_', '-')
        .toLowerCase();

const fallbackLocale = supportedCurrencyLocales[0];

export const resolveCurrencyLocale = (value?: string) => {
    if (!value) {
        return fallbackLocale;
    }

    const normalized = normalizeLocale(value);

    return (
        supportedCurrencyLocales.find(
            (item) => normalizeLocale(item.locale) === normalized,
        ) || fallbackLocale
    );
};

export const getCurrencySymbol = (locale: string, currency: string) => {
    const parts = new Intl.NumberFormat(locale, {
        style: 'currency',
        currency,
        currencyDisplay: 'symbol',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).formatToParts(0);

    return (
        parts.find((part) => part.type === 'currency')?.value || currency
    );
};

export const formatCurrencyNumber = (value: string, locale: string) => {
    if (!value || value === '.') {
        return '';
    }

    const [wholePart, decimalPart = ''] = value.split('.');
    const normalizedWholePart = wholePart.replace(/^0+(?=\d)/, '') || '0';
    const hasDecimalPart = value.includes('.');
    const hasTrailingDot = hasDecimalPart && decimalPart.length === 0;
    const parsed = Number(`${normalizedWholePart}.${decimalPart || '0'}`);

    if (Number.isNaN(parsed)) {
        return value;
    }

    const minimumFractionDigits = hasTrailingDot ? 0 : decimalPart.length;
    const maximumFractionDigits = decimalPart.length;

    const formatter = new Intl.NumberFormat(locale, {
        minimumFractionDigits,
        maximumFractionDigits,
    });

    const formatted = formatter.format(parsed);

    if (hasTrailingDot) {
        return `${formatted}.`;
    }

    return formatted;
};

export const sanitizeCurrencyValue = (value: string) => {
    let next = value.replace(/[^0-9.]/g, '');

    const firstDotIndex = next.indexOf('.');
    if (firstDotIndex !== -1) {
        next =
            next.slice(0, firstDotIndex + 1) +
            next.slice(firstDotIndex + 1).replace(/\./g, '');
    }

    return next;
};
