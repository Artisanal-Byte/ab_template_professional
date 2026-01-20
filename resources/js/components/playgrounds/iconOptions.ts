import lucideIcons from '@iconify/json/json/lucide.json';

const formatIconLabel = (name: string) =>
  name
    .replace(/-/g, ' ')
    .replace(/\b\w/g, (char) => char.toUpperCase());

export const lucideIconOptions = Object.keys(lucideIcons.icons)
  .sort((a, b) => a.localeCompare(b))
  .map((name) => ({
    label: formatIconLabel(name),
    value: name,
  }));
