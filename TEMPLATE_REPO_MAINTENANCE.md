# Template Repo Maintenance Instructions

This file belongs only in the template repository.

Delete this file after cloning the template into a real app project, unless the new project itself is also intended to become a template.

## Purpose

Use this file when maintaining the template repository itself.

The template repo should contain reusable AI-readable documentation so new app projects do not need to rediscover the same design system, component inventory, layouts, conventions, and guardrails every time.

## Canonical template documentation

Maintain these files inside the template repo:

```text
.ai/template/
  profile.md
  design-system.md
  component-inventory.md
  usage-rules.md
  change-log.md
```

### `.ai/template/profile.md`

High-level template identity and architecture.

Include:

- template name
- template purpose
- supported project type
- stack
- major packages only, no patch-version noise
- auth/scaffolding assumptions
- frontend structure
- backend structure
- routing/page conventions
- design-system summary
- what the template already provides
- what app projects are expected to add
- what should not be changed casually

### `.ai/template/design-system.md`

Design-system rules and visual language.

Include:

- typography conventions
- spacing conventions
- color/theme approach
- dark mode behavior
- layout patterns
- page shell patterns
- form-control rules
- default control heights
- button variants
- input/select/search-select behavior
- modal/dialog/drawer behavior
- table/list/card patterns
- loading/empty/error states
- accessibility basics
- forbidden UI drift

### `.ai/template/component-inventory.md`

Inventory of reusable components and primitives.

For each component, include:

- path
- purpose
- when to use
- important props/events/slots
- common examples
- related components
- do-not-duplicate notes

Group by category:

- layouts
- navigation
- buttons/actions
- forms
- inputs/selects/search controls
- modals/dialogs/drawers
- tables/lists/cards
- feedback/toasts/alerts
- icons/media
- utilities/helpers

### `.ai/template/usage-rules.md`

Agent-facing rules for using the template.

Include:

- read this before creating UI primitives
- prefer existing components
- do not duplicate primitives
- ask before adding a new primitive
- preserve design-system behavior
- preserve dark mode
- preserve accessibility
- preserve form-control consistency
- document reusable template-level improvements

### `.ai/template/change-log.md`

Template-maintenance changelog.

Record template-level changes made directly in the template repo.

Use:

```md
## DD-MM-YYYY — Short title

### Changed
- ...

### Why
- ...

### Files
- `path/to/file`

### Migration note for existing app projects
- ...
```

## Template profiling process

When creating or refreshing template documentation:

1. Read `AGENTS.md`.
2. Read relevant `.ai/rules/*.md`.
3. Inspect the repo lightly first.
4. Read:
   - `composer.json`
   - `package.json`
   - Vite config
   - Tailwind/theme CSS
   - route files
   - `resources/js` structure
   - layouts
   - pages
   - components
   - form controls
   - dialogs/modals
   - tables/lists/cards
   - auth scaffolding
   - shared helpers/utilities
5. Create or update `.ai/template/*` docs.
6. Do not rewrite docs from scratch if a targeted update is enough.
7. Do not invent capabilities the template does not actually provide.
8. Mark uncertain findings as assumptions or questions.
9. Keep the docs useful for AI execution, not marketing.

## When changing the template repo

For any change to reusable template behavior, update template docs in the same slice.

Update `.ai/template/*` when changing:

- layouts
- app shell
- navigation
- theme tokens
- design-system primitives
- form controls
- dialogs/modals/drawers
- tables/lists/cards
- reusable utilities
- route/page conventions
- auth scaffolding
- testing conventions
- package/dependency assumptions

## New primitive rule

Before adding a new reusable primitive:

1. Check the component inventory.
2. Check existing usages.
3. Confirm no existing primitive can be extended safely.
4. Add the primitive.
5. Add usage notes to `component-inventory.md`.
6. Add design rules to `design-system.md` or `usage-rules.md`.
7. Add a changelog entry.

## App-project compatibility

The template docs must help cloned app projects answer:

- what already exists?
- what should be reused?
- what should not be duplicated?
- what can be customized per app?
- what changes should be ported back to the template?
- what changes are app-specific and should not go back?

## Do not include

- temporary project-specific business rules
- client-specific requirements
- app-specific domain model
- app-specific specs
- app-specific decisions
- secrets
- credentials
- environment-specific values
- long generated examples that waste context

## Recommended maintenance command to give an agent

```text
Read AGENTS.md and this template-maintenance file.
Inspect the template repo and update `.ai/template/*` so future cloned projects can understand the template without rediscovering it.
Do not change application code unless documentation reveals a broken or stale template convention and you ask first.
```
