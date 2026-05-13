# AB Component Inventory

Reusable components organized by category.

---

## Layouts

### `resources/js/components/AppShell.vue`

- **Purpose**: Outer wrapper for app layouts. Provides flex container.
- **When to use**: Root wrapper for any page layout.
- **Props**: `variant` — `'header'` or `'sidebar'` (default: `'sidebar'`).
- **Related**: `AppHeaderLayout`, `AppSidebarLayout`, `AuthCardLayout`, etc.

### `resources/js/components/AppHeader.vue`

- **Purpose**: Top navigation bar with logo, breadcrumbs, nav tabs, user menu, theme toggle.
- **When to use**: In header-based layouts.
- **Props**: `breadcrumbs` — array of `BreadcrumbItem[]`.
- **Slots**: default (content area).
- **Related**: `AppBreadcrumbs`, `NavMain`, `UserMenuContent`, `ThemeToggle`.

### `resources/js/components/AppSidebar.vue`

- **Purpose**: Collapsible sidebar with logo, main nav, footer nav, user menu.
- **When to use**: In sidebar-based layouts.
- **Props**: `collapsed` — boolean (default: false).
- **Related**: `NavMain`, `NavFooter`, `NavUser`, `AppLogo`.

### `resources/js/components/AppSidebarHeader.vue`

- **Purpose**: Sidebar header component.
- **When to use**: Inside sidebar layouts.
- **Type**: Helper component.

### `resources/js/components/AppContent.vue`

- **Purpose**: Page content wrapper.
- **When to use**: Inside layout shells for page content.
- **Type**: Helper component.

### `resources/js/layouts/app/AppHeaderLayout.vue`

- **Purpose**: Layout wrapper for header-based pages.
- **When to use**: As the page layout for header-nav apps.

### `resources/js/layouts/app/AppSidebarLayout.vue`

- **Purpose**: Layout wrapper for sidebar-based pages.
- **When to use**: As the page layout for sidebar-nav apps.

### `resources/js/layouts/auth/AuthCardLayout.vue`

- **Purpose**: Centered card auth layout.
- **When to use**: Login, register, reset password pages.

### `resources/js/layouts/auth/AuthSplitLayout.vue`

- **Purpose**: Split-screen auth layout (logo + form).
- **When to use**: Welcome/login pages with branding area.

### `resources/js/layouts/auth/AuthSimpleLayout.vue`

- **Purpose**: Minimal auth layout.
- **When to use**: Simple auth pages without card/split styling.

### `resources/js/layouts/AuthLayout.vue`

- **Purpose**: Base auth layout wrapper.
- **When to use**: Parent for all auth layouts.

### `resources/js/layouts/settings/Layout.vue`

- **Purpose**: Settings page layout wrapper.
- **When to use**: Settings pages (profile, password, appearance, 2FA).

---

## Navigation

### `resources/js/components/AppBreadcrumbs.vue`

- **Purpose**: Breadcrumb trail display.
- **When to use**: Inside `AppHeader.vue` or any location needing breadcrumb navigation.
- **Props**: receives breadcrumbs from parent via `AppHeader.breadcrumbs`.
- **Do-not-duplicate**: This is the only breadcrumb component. Do not create another.

### `resources/js/components/NavMain.vue`

- **Purpose**: Main sidebar navigation items.
- **When to use**: Inside `AppSidebar.vue`.
- **Props**: `items` (NavItem[]), `collapsed` (boolean).
- **Related**: `NavItemLink`, `InlineDropdown`.

### `resources/js/components/NavUser.vue`

- **Purpose**: User menu in sidebar (avatar + name + dropdown).
- **When to use**: Inside `AppSidebar.vue` footer area.
- **Props**: `collapsed` (boolean).
- **Related**: `UserInfo`, `DropdownMenu`, `UserMenuContent`.

### `resources/js/components/NavFooter.vue`

- **Purpose**: Footer navigation items in sidebar.
- **When to use**: Inside `AppSidebar.vue` below main nav.
- **Props**: `items` (NavItem[]), `collapsed` (boolean), `class`.
- **Related**: `NavItemLink`.

### `resources/js/components/NavItemLink.vue`

- **Purpose**: Single navigation link item with active state styling.
- **When to use**: Inside `NavMain` or `NavFooter`.
- **Props**: `item` (NavItem), `collapsed` (boolean), `active` (boolean).
- **Type**: Internal navigation primitive.

### `resources/js/components/NavDrawer.vue`

- **Purpose**: Responsive drawer — collapsible desktop sidebar + mobile slide-in via Dialog.
- **When to use**: For responsive sidebar navigation.
- **Props**: `desktopWidth` (default: 'w-64'), `collapsedWidth` (default: 'w-16'), `mobileWidth` (default: 'w-[280px]'), `desktopClass`, `mobileClass`.
- **Events**: `update:desktopOpen`, `update:mobileOpen`.
- **Slots**: default (receives `{ collapsed }` boolean).
- **Related**: `Dialog`.

### `resources/js/components/TextLink.vue`

- **Purpose**: Styled anchor link for text navigation.
- **When to use**: Anywhere a text link is needed.
- **Type**: Helper component.

### `resources/js/components/ThemeToggle.vue`

- **Purpose**: Theme switcher (light/dark/system).
- **When to use**: In headers, settings, or anywhere for theme switching.
- **Related**: `useAppearance()`, `AppearanceTabs`.

### `resources/js/components/AppearanceTabs.vue`

- **Purpose**: Tab-based appearance (theme) switcher.
- **When to use**: In settings/appearance page.
- **Type**: App-specific component (not in ui/).

---

## Buttons / Actions

### `resources/js/components/ui/Button.vue`

- **Purpose**: Primary button primitive with variants and sizes.
- **When to use**: Any clickable action button.
- **Props**:
  - `as`: string | Component (default: `'button'`)
  - `type`: string (default: `'button'`)
  - `variant`: `'primary'` | `'secondary'` | `'outline'` | `'ghost'` | `'success'` | `'info'` | `'warning'` | `'destructive'` | `'link'` (default: `'primary'`)
  - `size`: `'xs'` | `'sm'` | `'md'` | `'lg'` | `'xl'` | `'icon'` (default: `'md'`)
  - `disabled`: boolean (default: false)
- **Slots**: default (button content).
- **Classes**: `buttonBase(variant, size, extra)` from `uiClass.ts`.
- **Do-not-duplicate**: This is the only button primitive. Do not create another.

### `resources/js/components/ui/InlineDropdown.vue`

- **Purpose**: Inline expandable content triggered by a button.
- **When to use**: Quick actions, inline menus, expandable sections.
- **Props**:
  - `triggerLabel`, `triggerVariant`, `triggerSize`, `triggerClass`, `triggerDisabled`
  - `showChevron` (default: true)
  - `contentClass`, `width` (xs/sm/md/lg/xl/full)
  - `v-model:open` (boolean)
- **Slots**: `icon`, `trigger-content`, `default` (content).
- **Related**: `Button`, `Icon`.

---

## Forms

### `resources/js/components/ui/Label.vue`

- **Purpose**: Form label wrapper.
- **When to use**: Above or beside any form input.
- **Props**: `for` (string, optional).
- **Classes**: `labelBase(extra)` from `uiClass.ts`.
- **Do-not-duplicate**: Only label primitive.

### `resources/js/components/ui/Checkbox.vue`

- **Purpose**: Styled checkbox input.
- **When to use**: Boolean form fields, multi-select filters.
- **Props**: `defaultValue` (boolean, optional).
- **v-model**: boolean.
- **Classes**: 16x16px, rounded, border, accent-[var(--primary)], focus-visible:ring-2.

### `resources/js/components/ui/Radio.vue`

- **Purpose**: Styled radio input.
- **When to use**: Single-choice form fields.
- **Props**: none beyond standard input attrs.
- **v-model**: string.
- **Classes**: 16x16px, rounded-full, border, accent-[var(--primary)].

### `resources/js/components/ui/RadioPill.vue`

- **Purpose**: Pill-style single radio option.
- **When to use**: Displaying a single radio option as a pill (used in RadioPillGroup).
- **Props**: `name` (required, string), `option` (required, SelectOption), `disabled` (boolean).
- **v-model**: string (selected value).
- **Slots**: default (option label).

### `resources/js/components/RadioPillGroup.vue`

- **Purpose**: Group of RadioPill options.
- **When to use**: Pill-style single-choice selection.
- **Type**: Composition component.

### `resources/js/components/ui/FormError.vue`

- **Purpose**: Display validation/form errors.
- **When to use**: Below any form input with an `error` prop.
- **Props**:
  - `error`: string | array | object (default: '')
  - `variant`: `'inline'` | `'modal'` (default: `'inline'`)
  - `title`: string (default: 'Something went wrong.')
  - `icon`: string (default: 'lucide:alert-circle')
  - `class`: string
- **Behavior**: Flattens array/object errors to list. Wraps `Alert` for modal variant.
- **Do-not-duplicate**: Only error display primitive.

---

## Inputs / Selects / Search Controls

### `resources/js/components/ui/Input.vue`

- **Purpose**: Text/number/email/etc. input with error display.
- **When to use**: Standard text form fields.
- **Props**:
  - `size`: `'sm'` | `'md'` | `'lg'` (default: `'md'`)
  - `defaultValue`: string | number (optional)
  - `error`: string | array | object (default: '')
- **v-model**: string | number.
- **Classes**: `inputBase(size, extra)` from `uiClass.ts`.
- **Do-not-duplicate**: Only text input primitive.

### `resources/js/components/ui/NumberInput.vue`

- **Purpose**: Numeric input with keyboard filtering.
- **When to use**: Number-only form fields.
- **Props**: `size` (sm/md/lg), `error`.
- **v-model**: string.
- **Behavior**: Filters non-numeric keys, allows single decimal point.

### `resources/js/components/ui/CurrencyInput.vue`

- **Purpose**: Locale-aware currency input with symbol prefix.
- **When to use**: Money/price form fields.
- **Props**:
  - `size` (sm/md/lg)
  - `locale` (default: `'en-IN'`)
  - `defaultValue` (string | number)
  - `error`
- **v-model**: string.
- **Composables**: `useCurrencyLocale` for formatting/symbol/locale resolution.
- **Classes**: `inputBase(size) + 'pl-9 text-right'`.

### `resources/js/components/ui/DateTimePicker.vue`

- **Purpose**: Custom clock/calendar date-time picker.
- **When to use**: Date and/or time form fields.
- **Props**:
  - `mode`: `'date'` | `'time'` | `'datetime'` (default: `'date'`)
  - `format`: string (default: `'DD-MM-YYYY'`)
  - `timeFormat`: string (default: `'HH:mm'`)
  - `timeStep`: number (default: 15)
  - `size` (sm/md/lg)
  - `placeholder`: string
  - `error`
- **v-model**: string (ISO format).
- **Composables**: `DateTimePicker.ts` for parsing/formatting.
- **UI**: Custom polar clock + calendar grid in Reka UI popover.

### `resources/js/components/ui/InputOTP.vue`

- **Purpose**: OTP/code input with multiple slots.
- **When to use**: Two-factor authentication, verification codes.
- **Props**:
  - `length` / `otpLength`: number (default: 6)
  - `separatorAt`: number (default: 0)
- **v-model**: string.
- **Dependency**: `vue-input-otp`.

### `resources/js/components/ui/Select.vue`

- **Purpose**: Single-select dropdown.
- **When to use**: Dropdown selection from a list of options.
- **Props**:
  - `options`: SelectOption[] (default: [])
  - `name`: string (default: '')
  - `placeholder`: string (default: 'Select an option')
  - `disabled`: boolean (default: false)
  - `width`: `'xs'` | `'sm'` | `'md'` | `'lg'` | `'xl'` | `'full'` (default: `'md'`)
  - `side`: `'top'` | `'bottom'` (default: `'bottom'`)
  - `sideOffset`: number (default: 6)
  - `loading`: boolean (default: false)
  - `noResultsText`: string (default: 'No results')
  - `error`: string | array | object (default: '')
- **v-model**: string | number | object.
- **Slots**: none (controlled via props).
- **Related**: `ListboxDropdown`, `SelectTrigger`, `useSelectDisplay`.

### `resources/js/components/ui/SearchAndSelect.vue`

- **Purpose**: Searchable single-select with create-new capability.
- **When to use**: Large option lists requiring search, or options that users may need to create.
- **Props**: Same as Select + `searchPlaceholder` (default: 'Search...'), `allowCreate` (default: false).
- **Emits**: `create` (when allowCreate and user creates new option).
- **v-model**: string | number | object.

### `resources/js/components/ui/MultiSelect.vue`

- **Purpose**: Multi-select with chip display.
- **When to use**: Multiple selection from a list.
- **Props**: Same as SearchAndSelect + `maxChips` (default: 1).
- **v-model**: array.
- **Display**: Selected values shown as `Badge` chips above input.

### `resources/js/components/ui/ListboxDropdown.vue`

- **Purpose**: Low-level listbox popover for select components.
- **When to use**: Building custom select/dropdown components.
- **Props**:
  - `options`: array
  - `selectedValues`: array (default: [])
  - `filterText`: string (default: '')
  - `width`: xs/sm/md/lg/xl/full (default: md)
  - `side`, `sideOffset`, `contentClass`, `maxHeight` (default: '18rem'), `matchTriggerWidth` (default: true)
  - `loading`: boolean (default: false)
  - `noResultsText`: string (default: 'No results')
  - `closeOnSelect`: boolean (default: true)
  - `openOnArrowKeys`: boolean (default: true)
  - `focusSearchOnOpen`: boolean (default: false)
- **Emits**: `select` (option selected).
- **v-model:open**: boolean.
- **Slots**: `trigger`, `header`, `footer`, `loading`, `empty`, `default`.
- **Related**: `Select`, `SearchAndSelect`, `MultiSelect`, `PopoverBase`.

### `resources/js/components/ui/SelectTrigger.vue`

- **Purpose**: Trigger display for Select/MultiSelect.
- **When to use**: Inside Select or MultiSelect components.
- **Type**: Internal primitive.

### `resources/js/components/ui/RichTextEditor.vue`

- **Purpose**: TipTap-based rich text editor.
- **When to use**: Content fields requiring formatting (bold, italic, links, images, tables, etc.).
- **Props**:
  - `placeholder`: string (default: 'Start writing...')
  - `disabled`: boolean (default: false)
  - `mentionItems`: array (default: [])
  - `disabledActions`: array (default: [])
  - `uploadImage`: function | null (default: null)
  - `error`: string | array | object (default: '')
- **v-model**: string (HTML).
- **v-model:files**: object (files map for image uploads).
- **Composables**: `useRichTextEditor.ts`.
- **Lib**: `richText.ts` — `buildRichTextFormData()` for multipart submission.
- **Do-not-duplicate**: Only rich text editor primitive.

---

## Modals / Dialogs / Drawers

### `resources/js/components/ui/Dialog.vue`

- **Purpose**: Modal dialog wrapper.
- **When to use**: Confirmation dialogs, modals, side panels.
- **Props**:
  - `modal`: boolean (default: true)
  - `transition`: `'scale'` | `'slide-left'` (default: `'scale'`)
  - `size`: `'sm'` | `'md'` | `'lg'` (default: `'sm'`)
  - `contentClass`: string (default: '')
  - `bodyClass`: string (default: '')
  - `showClose`: boolean (default: true)
- **v-model:open**: boolean.
- **Slots**: `trigger`, `title`, `description`, `default` (body), `footer` (receives `{ close }` function).
- **Related**: Reka UI Dialog primitives.

### `resources/js/components/ui/PopoverBase.vue`

- **Purpose**: Low-level popover wrapper for Reka UI.
- **When to use**: Building dropdowns, selects, tooltips, or any popover content.
- **Props**:
  - `side`, `align`, `sideOffset`, `width`, `contentClass`, `matchTriggerWidth`, `triggerWidthVar`
  - `rootComponent`, `triggerComponent`, `contentComponent`, `portalComponent` (for component swapping)
- **v-model:open**: boolean.
- **Slots**: `trigger`, `default`.
- **Related**: `DropdownMenu`, `Select`, `DateTimePicker`.

### `resources/js/components/ui/DropdownMenu.vue`

- **Purpose**: Button-triggered dropdown menu.
- **When to use**: Action menus, user menus, bulk action menus.
- **Props**:
  - `side`, `align`, `sideOffset`, `contentClass`, `width`, `menuWidth`
  - `triggerLabel`, `triggerVariant`, `triggerSize`, `triggerClass`, `triggerDisabled`, `triggerTestId`
- **v-model:open**: boolean.
- **Slots**: `trigger-content`, `default` (menu content).
- **Related**: `Button`, `PopoverBase`, Reka UI DropdownMenu.
- **Do-not-duplicate**: Only dropdown menu primitive.

### `resources/js/components/ui/Tooltip.vue`

- **Purpose**: Hover tooltip.
- **When to use**: Tooltips for buttons, icons, or any element needing context.
- **Props**:
  - `delayDuration`: number (default: 0)
  - `side`: 'top' | 'bottom' | 'left' | 'right' (default: 'top')
  - `align`: 'start' | 'center' | 'end' (default: 'center')
  - `sideOffset`: number (default: 6)
  - `contentClass`: string
  - `showArrow`: boolean (default: true)
- **Slots**: `trigger`, `default` (tooltip content).
- **Related**: Reka UI Tooltip primitives.

---

## Tables / Lists / Cards

### `resources/js/components/PaginatedTable.vue`

- **Purpose**: Full-featured paginated table with search, sort, and per-page controls.
- **When to use**: Admin panels, data listings, any table needing pagination.
- **Props**:
  - `columns`: TableColumn[]
  - `data`: Record<string, unknown>[]
  - `paginationMeta`: PaginationMeta
  - `searchQueryValue`, `searchFieldValue`, `searchFieldOptions`
  - `sortFieldValue`, `sortDirectionValue`, `sortFieldOptions`
  - `loading`: boolean (default: false)
  - `searchable`: boolean (default: true)
  - `showPerPage`: boolean (default: true)
  - `perPageOptions`: number[] (default: [10, 25, 50, 100])
  - `defaultPerPage`: number (default: 10)
  - `showJumpToPage`: boolean (default: false)
  - `showTopControls`: boolean (default: true)
  - `searchDebounce`: number (default: 300)
  - `borders`: 'horizontal' | 'full' (default: 'horizontal')
  - `striped`: boolean (default: false)
  - `hoverable`: boolean (default: true)
  - `emptyMessage`: string (default: 'No records found.')
  - `sortable`: boolean (default: true)
  - `defaultSort`: { key, direction }
- **Emits**: pagination/sort/search update events.
- **Types**: `TableColumn`, `PaginationMeta`, `SelectOption`, `SortState`.
- **Do-not-duplicate**: Only paginated table primitive.

### `resources/js/components/ui/Card.vue`

- **Purpose**: Collapsible card with header/content/footer.
- **When to use**: Content containers, settings panels, data display.
- **Props**:
  - `as`: string | Object (default: 'div')
  - `headerClass`, `contentClass`, `footerClass`: string
  - `collapsible`: boolean (default: false)
  - `startCollapsed`: boolean (default: false)
- **Slots**: `header`, `title`, `description`, `actions`, `default` (body), `footer`.
- **Do-not-duplicate**: Only card primitive.

### `resources/js/components/ui/Badge.vue`

- **Purpose**: Small rounded status pill.
- **When to use**: Status indicators, tags, counts.
- **Props**:
  - `variant`: 'neutral' | 'primary' | 'success' | 'warning' | 'error' (default: 'neutral')
  - `as`: string | Object (default: 'span')
- **Slots**: default (badge content).

---

## Feedback / Toasts / Alerts

### `resources/js/components/ui/Alert.vue`

- **Purpose**: Status message display.
- **When to use**: Success messages, warnings, errors, info banners.
- **Props**:
  - `variant`: 'neutral' | 'info' | 'success' | 'warning' | 'error' (default: 'neutral')
  - `as`: string | Object (default: 'div')
- **Slots**: `title`, `description`, `default` (fallback description).
- **Classes**: `rounded-lg border px-4 py-3 text-sm` + variant-specific colors.

### `resources/js/components/ui/Spinner.vue`

- **Purpose**: Loading spinner.
- **When to use**: Loading states, async operations.
- **Props**: `size`: 'sm' | 'md' | 'lg' (default: 'md').
- **ARIA**: role=status, aria-live=polite.

### `resources/js/components/ui/Skeleton.vue`

- **Purpose**: Shimmer placeholder for loading states.
- **When to use**: Skeleton screens during data loading.
- **Props**: none (passes through all attrs).
- **Classes**: `rounded-md skeleton-shimmer`.

---

## Icons / Media

### `resources/js/components/Icon.vue`

- **Purpose**: Iconify icon renderer.
- **When to use**: Anywhere an icon is needed.
- **Usage**: `<Icon name="lucide:icon-name" class="h-5 w-5" />`.
- **Dependency**: `@iconify/vue`.

### `resources/js/components/ui/Avatar.vue`

- **Purpose**: User avatar with fallback to initials.
- **When to use**: User display, user menus, sidebar user info.
- **Props**:
  - `size`: 'sm' | 'md' | 'lg' (default: 'md')
  - `src`: string (default: '')
  - `alt`: string (default: '')
  - `fallback`: string (default: '')
- **Slots**: `image` (custom img), `fallback` (custom fallback content).
- **Behavior**: Auto-fallback to initials when image fails to load.
- **Size classes**: sm (h-8 w-8), md (h-10 w-10), lg (h-12 w-12).

### `resources/js/components/UserInfo.vue`

- **Purpose**: User name + email + online indicator display.
- **When to use**: Sidebar user area, header user menu.
- **Props**: `user` (User object), `compact` (boolean).

### `resources/js/components/UserMenuContent.vue`

- **Purpose**: Dropdown content for user menu (settings links, logout).
- **When to use**: Inside `DropdownMenu` in sidebar or header.

---

## Utilities / Helpers

### `resources/js/components/ui/Separator.vue`

- **Purpose**: Horizontal or vertical divider.
- **When to use**: Visual separation in layouts, menus, dialogs.
- **Props**: `orientation`: 'horizontal' | 'vertical' (default: 'horizontal').

### `resources/js/components/Heading.vue`

- **Purpose**: Page heading display.
- **When to use**: Page titles, section headers.

### `resources/js/components/HeadingSmall.vue`

- **Purpose**: Small heading display.
- **When to use**: Subsection titles.

### `resources/js/components/DeleteUser.vue`

- **Purpose**: Account deletion dialog.
- **When to use**: Settings > profile page.

### `resources/js/components/TwoFactorRecoveryCodes.vue`

- **Purpose**: Display 2FA recovery codes.
- **When to use**: After 2FA setup.

### `resources/js/components/TwoFactorSetupModal.vue`

- **Purpose**: 2FA setup modal.
- **When to use**: Settings > two-factor page.

### `resources/js/components/PlaceholderPattern.vue`

- **Purpose**: Shimmer pattern placeholder.
- **When to use**: Empty state illustrations.

---

## Composables

### `resources/js/composables/useAppearance.ts`

- **Purpose**: Dark/light/system theme toggle.
- **Returns**: `appearance` ref, `toggleAppearance()`, `updateAppearance()`.

### `resources/js/composables/useCurrencyLocale.ts`

- **Purpose**: Currency formatting, symbol resolution, locale detection.
- **Exports**: `formatCurrencyNumber()`, `getCurrencySymbol()`, `resolveCurrencyLocale()`, `sanitizeCurrencyValue()`.

### `resources/js/composables/useInitials.ts`

- **Purpose**: Convert name string to initials.
- **Returns**: `getInitials(name)` function.

### `resources/js/composables/useRichTextEditor.ts`

- **Purpose**: TipTap editor setup and configuration.
- **Returns**: Editor instance, toolbar actions.

### `resources/js/composables/useSelectDisplay.ts`

- **Purpose**: Select option label/value extraction.
- **Exports**: `getOptionLabel(option)`, `getOptionValue(option)`.

### `resources/js/composables/useTwoFactorAuth.ts`

- **Purpose**: 2FA setup and recovery code management.

### `resources/js/composables/DateTimePicker.ts`

- **Purpose**: Date/time parsing, formatting, mask application.
- **Exports**: `parseDate()`, `formatDate()`, `applyDateMask()`, `buildCalendarCells()`, etc.

---

## Lib / Utilities

### `resources/js/lib/utils.ts`

- **Exports**: `cn()` (clsx + twMerge), `urlIsActive()`, `toUrl()`.

### `resources/js/lib/attrs.ts`

- **Exports**: `splitAttrs()` — separates className from other Vue attrs for proper class merging.

### `resources/js/lib/uiClass.ts`

- **Exports**: `inputBase()`, `buttonBase()`, `cardBase()`, `labelBase()`, `tableBaseClasses()`, `tableHeaderRowClasses()`, `tableHeaderCellClasses()`, `tableRowClasses()`, `tableCellClasses()`, `inputSizeClasses`, `buttonBaseClasses`, `buttonVariantClasses`, `buttonSizeClasses`.

### `resources/js/lib/richText.ts`

- **Exports**: `buildRichTextFormData(html, filesMap)` — builds FormData for rich text image uploads.

---

## Config

### `resources/js/config/navConfig.ts`

- **Exports**: `mainNavItems()` — returns NavItem[] for sidebar/header nav. `externalNavItems()` — returns external nav items.

---

## Playgrounds (dev-only)

Playground components for testing and demonstrating UI components. Disabled in production.

| Path | Component |
|---|---|
| `playgrounds/inputs/Playground.vue` | Input variants |
| `playgrounds/loading/Playground.vue` | Loading states |
| `playgrounds/modals/Playground.vue` | Modal variants |
| `playgrounds/selects/Playground.vue` | Select overview |
| `playgrounds/selects/SearchSelectPlayground.vue` | SearchAndSelect |
| `playgrounds/selects/StandardPlayground.vue` | Standard Select |
| `playgrounds/selects/MultiSelectPlayground.vue` | MultiSelect |
| `playgrounds/tables/Playground.vue` | PaginatedTable |
| `playgrounds/tooltips/Playground.vue` | Tooltip |
| `playgrounds/buttons/Playground.vue` | Button variants |
| `playgrounds/dropdowns/Playground.vue` | DropdownMenu |
| `playgrounds/alerts/Playground.vue` | Alert variants |
| `playgrounds/avatars/Playground.vue` | Avatar |
| `playgrounds/badges/Playground.vue` | Badge |
| `playgrounds/breadcrumbs/Playground.vue` | Breadcrumbs |
| `playgrounds/cards/Playground.vue` | Card |
| `playgrounds/checkbox_radio/Playground.vue` | Checkbox + Radio |
| `playgrounds/currency/Playground.vue` | CurrencyInput |
| `playgrounds/date_time/Playground.vue` | DateTimePicker |
| `playgrounds/errors/Playground.vue` | Error states |
| `playgrounds/icons/Playground.vue` | Icon |
| `playgrounds/otp/Playground.vue` | InputOTP |
| `playgrounds/rich_text/Playground.vue` | RichTextEditor |

Support files: `playgrounds/buildAttrList.ts`, `playgrounds/iconOptions.ts`, `playgrounds/options.ts`, `playgrounds/DrawerSection.vue`, `playgrounds/PlaygroundCodeBlock.vue`, `playgrounds/PlaygroundMetaPanel.vue`.
