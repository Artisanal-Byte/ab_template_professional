# AB Design System

## Visual language

OKLCH-based CSS custom property token system. All colors are defined in `resources/css/tokens.css` and mapped to Tailwind via `tailwind.preset.ts`.

### Token architecture

- **Base tokens**: `--color-background-light/dark`, `--color-foreground-light/dark`, `--color-card-light/dark`, etc. — raw OKLCH values.
- **Semantic tokens**: `--background`, `--foreground`, `--card`, `--primary`, etc. — mapped to base tokens per theme.
- **Derived tokens**: `--foreground-muted`, `--background-muted`, `--secondary-soft`, `--primary-border`, etc. — computed via `color-mix()` from semantic tokens.

### Color palette

| Token | Usage |
|---|---|
| `--background` | Page background |
| `--foreground` | Primary text |
| `--card` | Card/container background |
| `--card-foreground` | Card text |
| `--primary` | Primary action color |
| `--primary-foreground` | Text on primary background |
| `--border` / `--border-subtle` / `--border-muted` / `--border-strong` | Border variants |
| `--secondary` / `--secondary-soft` / `--secondary-muted` / `--secondary-hover` | Secondary surfaces |
| `--accent` | Accent color |
| `--info` / `--success` / `--warning` / `--error` | Status colors + soft/border variants |
| `--focus-ring` | Focus ring color |
| `--skeleton` / `--skeleton-highlight` | Shimmer loading |

## Layout conventions

### Shell layouts

- **AppShell** (`AppShell.vue`): outer wrapper. `variant="sidebar"` (default) for sidebar layout, `variant="header"` for header-only layout. Single root div, flex min-h-screen.
- **AppHeaderLayout** (`layouts/app/AppHeaderLayout.vue`): header-based layout with top navigation.
- **AppSidebarLayout** (`layouts/app/AppSidebarLayout.vue`): sidebar-based layout with collapsible sidebar.
- **AuthCardLayout** (`layouts/auth/AuthCardLayout.vue`): centered card auth page.
- **AuthSplitLayout** (`layouts/auth/AuthSplitLayout.vue`): split-screen auth (logo + form).
- **AuthSimpleLayout** (`layouts/auth/AuthSimpleLayout.vue`): minimal auth page.
- **Settings Layout** (`layouts/settings/Layout.vue`): settings page wrapper.

### Header

`AppHeader.vue` — 64px height, contains:
- Logo
- Breadcrumbs (`AppBreadcrumbs.vue`)
- Desktop nav tabs (from `mainNavItems()`)
- Right items (from `externalNavItems()`)
- User menu (`DropdownMenu` + `UserMenuContent`)
- Theme toggle
- Mobile menu trigger (uses `Dialog` with slide-in drawer)

### Sidebar

`AppSidebar.vue` — contains:
- Logo header (16px height)
- `NavMain` — main nav items with `InlineDropdown` for quick actions
- `NavFooter` — footer nav items
- `NavUser` — user menu with `DropdownMenu`

## Typography / spacing / theme conventions

- Typography follows Tailwind defaults. No custom font families in the template.
- Heading components: `Heading.vue` (page title), `HeadingSmall.vue` (section title).
- Spacing uses Tailwind's default scale (gap-1, gap-2, gap-3, gap-4, etc.).
- Max content width: `md:max-w-7xl` (80rem) for main content area.
- Sidebar: desktop `w-64` (16rem), collapsed `w-16` (4rem), mobile `w-[280px]`.
- Header: fixed `h-16` (4rem).

## Dark mode behavior

- Toggled via `.dark` class on `<html>` element.
- `useAppearance()` composable manages state (light/dark/system).
- All colors have light/dark base tokens in `tokens.css`.
- Derived tokens use `color-mix()` which works identically in both themes.
- Do not hardcode colors in components — always use token-based Tailwind classes.
- Do not add custom CSS that bypasses the token system.

## Form-control rules

### Default control heights

| Size | Height | Usage |
|---|---|---|
| sm | `h-8` (2rem) | Compact forms, inline |
| md | `h-10` (2.5rem) | Default, most forms |
| lg | `h-11` (2.75rem) | Prominent forms |

Shared by: `Input`, `NumberInput`, `CurrencyInput`, `DateTimePicker`, `Select`, `Button`.

### Input variants

| Component | Purpose | v-model type | Key props |
|---|---|---|---|
| `Input` | Text/number/email/etc. | `string \| number` | `size`, `defaultValue`, `error` |
| `NumberInput` | Numeric input with keyboard filtering | `string` | `size`, `error` |
| `CurrencyInput` | Locale-aware currency with symbol prefix | `string` | `size`, `locale`, `defaultValue`, `error` |
| `DateTimePicker` | Custom clock/calendar date-time picker | `string` (ISO) | `mode`, `format`, `timeFormat`, `timeStep`, `size`, `error` |
| `InputOTP` | OTP/code input | `string` | `length`/`otpLength`, `separatorAt` |

### Select variants

| Component | Purpose | v-model type | Key props |
|---|---|---|---|
| `Select` | Single-select dropdown | `string \| number \| object` | `options`, `name`, `placeholder`, `disabled`, `width`, `side`, `sideOffset`, `loading`, `noResultsText`, `error` |
| `SearchAndSelect` | Searchable single-select with create | `string \| number \| object` | Same as Select + `searchPlaceholder`, `allowCreate` (emits `create`) |
| `MultiSelect` | Multi-select with chips | `array` | Same as SearchAndSelect + `maxChips` |
| `InlineDropdown` | Inline expandable content | `boolean` (open) | `triggerLabel`, `triggerVariant`, `triggerSize`, `width`, `showChevron` |

### Form structure

All form controls with `error` prop render `FormError` below the input.
`FormError` accepts `string`, `array`, or `object` error formats and displays them as a list or single message.

## Default control heights (component level)

All form controls set their height at the component level via `inputSizeClasses` in `uiClass.ts`:
- `sm`: `h-8 px-2 text-sm`
- `md`: `h-10 px-3 text-sm`
- `lg`: `h-11 px-4 text-base`

Do not override these in consumer components.

## Button behavior

| Variant | Background | Text | Use case |
|---|---|---|---|
| `primary` | `--primary` | `--primary-foreground` | Primary action |
| `secondary` | `--secondary` | `--foreground` | Secondary action |
| `outline` | transparent | `--foreground` + border | Outlined action |
| `ghost` | transparent | `--foreground` | Tertiary/action-in-group |
| `success` | `--success` | `--foreground` | Success/confirm |
| `info` | `--info` | `--foreground` | Info action |
| `warning` | `--warning` | `--foreground` | Warning action |
| `destructive` | `--error` | `--foreground` | Delete/danger |
| `link` | transparent | `--primary` + underline | Link-style action |

Sizes: `xs` (h-6), `sm` (h-8), `md` (h-10), `lg` (h-11), `xl` (h-12), `icon` (h-10 w-10).

`as` prop allows rendering as any element/component (default: `button`).
`type` prop controls button type when `as="button"` (default: `button`).

## Select / SearchSelect behavior

- `Select` uses `ListboxDropdown` + `SelectTrigger` internally.
- `SearchAndSelect` adds a search input + `allowCreate` capability.
- `MultiSelect` adds chip display + array v-model.
- All use `SelectOption` type: `{ value?, label?, description?, group?, disabled? }`.
- Width options: `xs` (w-32), `sm` (w-40), `md` (w-56), `lg` (w-72), `xl` (w-80), `full` (w-full).
- Side: `top` or `bottom` (default: `bottom`).
- Loading state shows `Spinner` in dropdown.
- Empty state shows `noResultsText`.

## Modal / Dialog / Drawer behavior

### Dialog (`Dialog.vue`)

- Wraps Reka UI `DialogRoot`, `DialogContent`, `DialogOverlay`, `DialogTitle`, `DialogClose`, `DialogTrigger`, `DialogPortal`.
- Sizes: `sm` (w-32rem), `md` (w-60vw), `lg` (w-80vw).
- Transitions: `scale` (default, centered overlay), `slide-left` (full-height side panel).
- `showClose` (default: true) controls X button visibility.
- Slots: `trigger`, `title`, `description`, `default` (body), `footer` (receives `{ close }` helper).
- Footer slot receives `{ close }` function for programmatic close.

### PopoverBase (`PopoverBase.vue`)

- Wraps Reka UI `PopoverRoot`, `PopoverTrigger`, `PopoverContent`, `PopoverPortal`.
- Configurable root/trigger/content/portal components for flexibility.
- Width options: `xs` through `xl` + `full`.
- Used by: `DropdownMenu`, `Select`, `DateTimePicker`.

### DropdownMenu (`DropdownMenu.vue`)

- Button-triggered dropdown using `PopoverBase` + Reka UI `DropdownMenuRoot`.
- Trigger uses `Button` component with configurable variant/size.
- Slots: `trigger-content`, `default` (menu content).

### Drawer (`NavDrawer.vue`)

- Desktop: collapsible sidebar (`w-64` / `w-16`).
- Mobile: slide-in drawer via `Dialog` with `transition="slide-left"`.
- Slots: receives `{ collapsed }` boolean for both desktop and mobile.
- Not in `ui/` — it is a composition component.

## Table / List / Card patterns

### PaginatedTable (`PaginatedTable.vue`)

- Full-featured table with built-in pagination, search, sort, and per-page selector.
- Props: `columns` (TableColumn[]), `data`, `paginationMeta`, `searchQueryValue`, `searchFieldValue`, `sortFieldValue`, `sortDirectionValue`, `loading`, `searchable`, `showPerPage`, `borders`, `striped`, `hoverable`, `emptyMessage`, `sortable`, `defaultSort`, `showJumpToPage`, `searchDebounce`.
- Built-in loading state (Spinner overlay), empty state (emptyMessage).
- Emits: `update:searchQueryValue`, `update:searchFieldValue`, `update:sortFieldValue`, `update:sortDirectionValue`, `update:paginationMeta`.
- Uses `TableColumn` type: `{ key, label, sortable?, searchable?, width?, className?, headerClassName?, format? }`.

### Card (`Card.vue`)

- Collapsible card with header/content/footer sections.
- Props: `as`, `headerClass`, `contentClass`, `footerClass`, `collapsible`, `startCollapsed`.
- Slots: `header`, `title`, `description`, `actions`, `default` (body), `footer`.
- Header has border-bottom when not collapsed.

### Badge (`Badge.vue`)

- Small rounded pill: `inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-medium`.
- Variants: `neutral`, `primary`, `success`, `warning`, `error`.
- `as` prop for element type (default: `span`).

## Loading / Empty / Error states

| State | Component | Usage |
|---|---|---|
| Loading | `Spinner.vue` | SVG spinner, sizes sm/md/lg, role=status, aria-live=polite |
| Skeleton | `Skeleton.vue` | Shimmer placeholder via `.skeleton-shimmer` class |
| Empty | Built into PaginatedTable | `emptyMessage` prop |
| Alert | `Alert.vue` | Status messages: neutral/info/success/warning/error |
| FormError | `FormError.vue` | Validation errors: inline or modal variant |

## Accessibility basics

- All inputs have associated `<Label>` or aria-label.
- Focus visible: `focus-visible:ring-2 focus-visible:ring-focus-ring` on all interactive elements.
- Disabled states: `disabled:cursor-not-allowed disabled:opacity-50` on all controls.
- Semantic elements: `<button>` for actions, `<a>` for navigation, `<label>` for inputs.
- Screen reader only text: `sr-only` class for hidden labels.
- ARIA live: `aria-live="polite"` on Spinner.
- Keyboard accessible: all Reka UI components provide keyboard navigation.
- Color contrast: OKLCH colors chosen for WCAG AA compliance.

## Forbidden UI drift

- Do not add custom CSS that bypasses the token system.
- Do not change default control heights in consumer components.
- Do not create duplicate button/input/select/dialog/card/table components.
- Do not hardcode colors — always use token-based Tailwind classes.
- Do not break dark mode by using non-token colors.
- Do not remove focus-visible ring from interactive elements.
- Do not use `!important` overrides for design-system classes.
- Do not introduce new color variables without updating both light and dark themes.
