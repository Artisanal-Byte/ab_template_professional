# AB Usage Rules

Rules for AI coding agents working with the AB Template.

## Read template docs first

Before creating any UI primitive, layout, form control, navigation component, table, dialog, or shared UI element:

1. Read `.ai/template/profile.md` for context.
2. Read `.ai/template/component-inventory.md` to check if the component already exists.
3. Read `.ai/template/design-system.md` for design-system rules.

## Prefer existing components

- Use components from `resources/js/components/ui/` for all UI needs.
- Use layout wrappers from `resources/js/layouts/` for page structure.
- Use composables from `resources/js/composables/` for shared logic.
- Use utilities from `resources/js/lib/` for common helpers.

## Do not duplicate primitives

The following are **design-system primitives**. Do not create duplicates:

- Buttons: `Button.vue`
- Inputs: `Input.vue`, `NumberInput.vue`, `CurrencyInput.vue`, `DateTimePicker.vue`, `InputOTP.vue`
- Selects: `Select.vue`, `SearchAndSelect.vue`, `MultiSelect.vue`, `ListboxDropdown.vue`, `SelectTrigger.vue`, `InlineDropdown.vue`
- Forms: `Label.vue`, `Checkbox.vue`, `Radio.vue`, `RadioPill.vue`, `FormError.vue`
- Dialogs/Modals: `Dialog.vue`, `PopoverBase.vue`, `DropdownMenu.vue`, `Tooltip.vue`
- Tables: `PaginatedTable.vue`
- Cards: `Card.vue`
- Feedback: `Alert.vue`, `Badge.vue`, `Spinner.vue`, `Skeleton.vue`
- Media: `Avatar.vue`, `Icon.vue`
- Rich text: `RichTextEditor.vue`
- Layouts: `AppShell.vue`, `AppHeader.vue`, `AppSidebar.vue`, layout wrappers
- Navigation: `AppBreadcrumbs.vue`, `NavMain.vue`, `NavUser.vue`, `NavFooter.vue`, `NavItemLink.vue`, `NavDrawer.vue`

## Ask before adding new primitives

Before adding any new reusable component not in the inventory:

1. Check the component inventory (above).
2. Check existing usages.
3. Confirm no existing primitive can be extended safely.
4. Explain why existing primitives are insufficient.
5. Ask for confirmation.
6. Only then create the new primitive.
7. Add usage notes to `component-inventory.md`.
8. Add design rules to `design-system.md` or `usage-rules.md`.
9. Add a changelog entry to `change-log.md`.

## Preserve design-system behavior

- Do not change default control heights (sm: h-8, md: h-10, lg: h-11).
- Do not change button variants or sizes.
- Do not change the token system (`tokens.css`).
- Do not change the `uiClass.ts` class builders.
- Do not change the `cn()` utility behavior.

## Preserve dark mode

- All colors must use token-based Tailwind classes.
- Do not hardcode colors in components.
- Do not add custom CSS that bypasses the token system.
- When adding new colors, add both light and dark base tokens.

## Preserve accessibility

- All inputs must have associated labels.
- All interactive elements must have focus-visible states.
- All buttons must use semantic `<button>` elements.
- All disabled elements must have `disabled:cursor-not-allowed disabled:opacity-50`.
- Use `sr-only` for hidden labels.

## Preserve form-control consistency

- All form controls must use `inputSizeClasses` from `uiClass.ts` for height.
- All form controls with `error` prop must render `FormError` below the input.
- All form controls must support `v-model`.
- All form controls must support `disabled`.

## Document reusable improvements

When making changes that could benefit the template repo:

1. Record the change in `.ai/template_updates.md` (in the app project).
2. Include: change type, files changed, reusable value, porting notes, checks run.
3. These entries are candidates for manual porting back to the template repo.
