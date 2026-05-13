# 05 Inertia, Vue, and Wayfinder

## Inertia

- Inertia pages live in `resources/js/pages` unless project config says otherwise.
- Use `Inertia::render()` for server-side page responses.
- Use `<Link>` or `router.visit()` for client-side navigation.
- Do not use traditional anchors for internal Inertia navigation unless there is a deliberate reason.
- Use Inertia v3 patterns where useful:
  - deferred props
  - merge/infinite scroll
  - lazy loading on scroll
  - polling
  - prefetching
- When using deferred props, provide a proper empty/loading/skeleton state.

## Forms

- Prefer Inertia v3 `<Form>` for forms by default.
- Use Wayfinder form helpers with `<Form>` when appropriate.
- Use `useForm` only when:
  - the existing page pattern uses it
  - programmatic control is clearly simpler
  - dynamic/multistep/autosave/file-preview behavior makes `<Form>` awkward
- Do not mix `<Form>` and `useForm` patterns randomly in the same feature.
- Preserve validation error display and disabled/loading states.
- Reset behavior should be intentional.

## Vue SFCs

- Vue SFCs must have a single root element.
- Use `<script setup lang="ts">` unless explicitly told otherwise.
- Keep template logic simple.
- Extract non-trivial logic into named functions/computed values.
- Avoid multiline inline template handlers.
- Preserve existing component naming and folder conventions.

## Components

- Check existing components before creating new ones.
- Do not duplicate existing primitives.
- Before adding a new primitive, explain why existing primitives are insufficient and ask for confirmation.
- Reuse layouts, page shells, form controls, modals/dialogs, tables, and navigation patterns from the project/template.

## Wayfinder

- Use `search-docs` before implementing unfamiliar Wayfinder behavior when Boost is available.
- Prefer named imports for tree-shaking.
- Avoid default controller imports.
- Use generated route/controller helpers instead of hardcoded URLs where the project convention supports it.
- Regenerate Wayfinder types after route changes when needed.
- Keep Wayfinder rules compact: do not paste long examples into project rules.
