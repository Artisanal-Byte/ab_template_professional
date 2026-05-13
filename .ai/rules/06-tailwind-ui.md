# 06 Tailwind and UI

## Tailwind

- Use Tailwind utility-first styling.
- Avoid custom/raw CSS unless necessary.
- Respect existing visual language and design system.
- Think through class placement, order, priority, defaults, and repetition.
- Use parent/child class placement carefully to avoid duplicate class bundles.
- Use `gap-*` utilities for list spacing instead of margins.
- Preserve dark mode behavior where existing pages/components support it.

## Tailwind v4

- Use Tailwind CSS v4 patterns.
- Do not use deprecated v3 utilities.
- Do not use `corePlugins`.
- Tailwind v4 uses CSS-first config with `@theme`.
- Tailwind is imported with `@import "tailwindcss";`, not old `@tailwind` directives.
- Use slash opacity utilities such as `bg-black/50`, not old `bg-opacity-*` patterns.
- Use replacements for removed utilities:
  - `shrink-*` not `flex-shrink-*`
  - `grow-*` not `flex-grow-*`
  - `text-ellipsis` not `overflow-ellipsis`
  - `box-decoration-slice` / `box-decoration-clone` not deprecated decoration utilities

## Design system

- Prefer existing primitives and patterns.
- Do not create duplicate buttons, inputs, selects, dialogs, cards, tables, badges, or search controls.
- Before adding any new primitive component/functionality:
  1. inspect existing components
  2. explain why no existing primitive fits
  3. ask for confirmation
- When changing shared design-system/components, add/update a concise reusable note in the project's design-system memory file if one exists.
- Inputs, selects, search-select triggers, and comparable form controls must share the same default control height at component level.

## UI quality

- For established design-system projects, build production-grade UI/UX in the same slice after backend readiness so the feature can be verified on-site immediately.
- Defer only low-value polish when needed:
  - micro-animations
  - minor copy refinements
  - decorative flourishes
- Do not defer core UX flows behind temporary harnesses.
- Empty/loading/error states should be implemented when user experience would otherwise break.
- Keep accessibility basics:
  - labels for inputs
  - clear focus states
  - semantic buttons/links
  - keyboard-accessible controls when practical
