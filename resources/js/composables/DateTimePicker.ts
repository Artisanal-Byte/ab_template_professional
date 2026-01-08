export const parseFormatTokens = (format: string) =>
    format.match(/(DD|MM|YYYY|YY|[^DYM]+)/g) || [];

export const applyDateMask = (value: string, format: string) => {
    const digits = value.replace(/\D/g, '');
    const tokens = parseFormatTokens(format);
    let index = 0;
    let output = '';
    tokens.forEach((token) => {
        if (token === 'DD' || token === 'MM' || token === 'YY') {
            const chunk = digits.slice(index, index + 2);
            if (!chunk) {
                return;
            }
            output += chunk;
            index += 2;
        } else if (token === 'YYYY') {
            const chunk = digits.slice(index, index + 4);
            if (!chunk) {
                return;
            }
            output += chunk;
            index += 4;
        } else if (index < digits.length) {
            output += token;
        }
    });
    return output;
};

export const applyTimeMask = (value: string) => {
    const digits = value.replace(/\D/g, '');
    const hours = digits.slice(0, 2);
    const minutes = digits.slice(2, 4);
    if (!hours) {
        return '';
    }
    if (!minutes) {
        return hours;
    }
    return `${hours}:${minutes}`;
};

export const applyDateTimeMask = (value: string, format: string) => {
    const digits = value.replace(/\D/g, '');
    const tokens = parseFormatTokens(format);
    const dateDigitsLength = tokens.reduce((count, token) => {
        if (token === 'DD' || token === 'MM' || token === 'YY') {
            return count + 2;
        }
        if (token === 'YYYY') {
            return count + 4;
        }
        return count;
    }, 0);
    const dateDigits = digits.slice(0, dateDigitsLength);
    const timeDigits = digits.slice(dateDigitsLength, dateDigitsLength + 4);
    const datePart = applyDateMask(dateDigits, format);
    const timePart = applyTimeMask(timeDigits);
    if (timePart && datePart) {
        return `${datePart} ${timePart}`;
    }
    return datePart || timePart;
};

export const formatDate = (date: Date, format: string) => {
    const dd = String(date.getDate()).padStart(2, '0');
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const yyyy = String(date.getFullYear());
    const yy = yyyy.slice(-2);
    return format
        .replace(/DD/g, dd)
        .replace(/MM/g, mm)
        .replace(/YYYY/g, yyyy)
        .replace(/YY/g, yy);
};

export const formatDateTime = (date: Date, time: string, format: string) =>
    `${formatDate(date, format)} ${time}`;

export const formatIsoDate = (date: Date) =>
    `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(
        date.getDate(),
    ).padStart(2, '0')}`;

export const formatIsoDateTime = (date: Date, time: string) =>
    `${formatIsoDate(date)} ${time}`;

export const parseIsoDate = (value: string) => {
    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!match) {
        return null;
    }
    const year = Number(match[1]);
    const month = Number(match[2]);
    const day = Number(match[3]);
    const candidate = new Date(year, month - 1, day);
    if (
        candidate.getFullYear() !== year ||
        candidate.getMonth() !== month - 1 ||
        candidate.getDate() !== day
    ) {
        return null;
    }
    return candidate;
};

export const parseDate = (value: string, format: string) => {
    const tokens = format.match(/(DD|MM|YYYY|YY)/g) || [];
    const numbers = value.match(/\d+/g) || [];
    if (tokens.length !== numbers.length) {
        return null;
    }
    const parts: Record<string, number> = {};
    tokens.forEach((token, index) => {
        parts[token] = Number(numbers[index]);
    });
    const rawYear =
        typeof parts.YYYY === 'number'
            ? parts.YYYY
            : typeof parts.YY === 'number'
              ? parts.YY
              : NaN;
    if (!rawYear || Number.isNaN(rawYear)) {
        return null;
    }
    const year =
        typeof parts.YYYY === 'number'
            ? rawYear
            : rawYear < 70
              ? 2000 + rawYear
              : 1900 + rawYear;
    const month = parts.MM ?? NaN;
    const day = parts.DD ?? NaN;
    if (!month || !day || month < 1 || month > 12 || day < 1 || day > 31) {
        return null;
    }
    const candidate = new Date(year, month - 1, day);
    if (
        candidate.getFullYear() !== year ||
        candidate.getMonth() !== month - 1 ||
        candidate.getDate() !== day
    ) {
        return null;
    }
    return candidate;
};

export const parseTime = (value: string) => {
    const match = value.match(/(\d{1,2}):(\d{2})/);
    if (!match) {
        return null;
    }
    const hours = Number(match[1]);
    const minutes = Number(match[2]);
    if (
        Number.isNaN(hours) ||
        Number.isNaN(minutes) ||
        hours < 0 ||
        hours > 23 ||
        minutes < 0 ||
        minutes > 59
    ) {
        return null;
    }
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
};

export const getTimeParts = (value: string) => {
    const parsed = parseTime(value);
    if (!parsed) {
        return { hour: 0, minute: 0 };
    }
    const [hour, minute] = parsed.split(':').map(Number);
    return { hour, minute };
};

export const normalizeMinute = (minute: number, step: number) =>
    Math.floor(minute / step) * step;

export const buildMinuteOptions = (step: number) => {
    const safeStep = step > 0 ? step : 15;
    const values: number[] = [];
    for (let minute = 0; minute < 60; minute += safeStep) {
        values.push(minute);
    }
    return values;
};

export const formatMonthLabel = (date: Date) =>
    date.toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    });

export const buildCalendarCells = (viewDate: Date) => {
    const start = new Date(viewDate.getFullYear(), viewDate.getMonth(), 1);
    const startDay = (start.getDay() + 6) % 7;
    const daysInMonth = new Date(
        viewDate.getFullYear(),
        viewDate.getMonth() + 1,
        0,
    ).getDate();
    const cells: Array<Date | null> = [];
    for (let i = 0; i < startDay; i += 1) {
        cells.push(null);
    }
    for (let day = 1; day <= daysInMonth; day += 1) {
        cells.push(
            new Date(viewDate.getFullYear(), viewDate.getMonth(), day),
        );
    }
    while (cells.length % 7 !== 0) {
        cells.push(null);
    }
    return cells;
};

export const getPolarStyle = (index: number, total: number, radius: number) => {
    const angle = (index / total) * 2 * Math.PI;
    return {
        left: `calc(50% + ${Math.sin(angle) * radius}px)`,
        top: `calc(50% - ${Math.cos(angle) * radius}px)`,
        transform: 'translate(-50%, -50%)',
    };
};
