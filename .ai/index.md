# AB Template — AI Loading Map

## Canonical source of truth

- `AGENTS.md` is the root source of truth for all agent behavior.
- Read `AGENTS.md` first before any other rule file.

## Rule files (loaded selectively)

Modular rules under `.ai/rules/*` are loaded only as needed based on task type. See the rule loading map in `AGENTS.md`.

| File | Purpose |
|---|---|
| `00-project-init.md` | Project/spec initialization (only for new projects) |
| `01-operating-mode.md` | Work style, safety, context control |
| `02-project-context.md` | Stack, source hierarchy, template-aware behavior |
| `03-coding-standards.md` | Naming, style, PHP/JS/Vue conventions |
| `04-laravel.md` | Laravel 12 conventions, PostgreSQL, Eloquent |
| `05-inertia-vue-wayfinder.md` | Inertia, Vue SFC, Wayfinder patterns |
| `06-tailwind-ui.md` | Tailwind v4, design system guardrails |
| `07-testing-quality.md` | Pest, fast-mode testing, handoff quality |
| `08-boost-tools.md` | Laravel Boost tool usage |
| `09-git-handoff.md` | Git boundaries, commit style, handoff format |

Do not load all rule files by default. Load only the files relevant to the current task.

## Template documentation

The `.ai/template/*` directory documents the reusable AB Template. Cloned app projects should treat these as canonical documentation.

| File | Purpose |
|---|---|
| `template/profile.md` | Template identity, stack, structure, conventions |
| `template/design-system.md` | Visual language, tokens, form controls, patterns |
| `template/component-inventory.md` | Full reusable component inventory |
| `template/usage-rules.md` | Agent-facing rules for template usage |
| `template/change-log.md` | Template-level changelog |

When working on a cloned app project:
1. Read `.ai/template/*` before creating UI primitives.
2. Do not regenerate template docs per project.
3. Record reusable improvements in `.ai/template_updates.md`.

## Template updates tracker

`.ai/template_updates.md` is inherited by cloned app projects. It records reusable improvements discovered during app development that may later be ported back to the template repo.

**This file is for app projects ONLY.** Do not update it in the template repo itself.

## Template repo maintenance

`TEMPLATE_REPO_MAINTENANCE.md` exists only in the template repo. It contains instructions for maintaining the template documentation.

**This file should be deleted from cloned app projects** (unless the new project is also intended to become a template).

## Specs

`.ai/specs/*` contains only remaining or future template work.

- See `specs/README.md` for guidance.
- See `specs/template-backlog.md` for candidate remaining items.
- Do not backfill specs for already-completed template features.

## Legacy files

- `rules.md` at the repo root is historical only. Do not use unless explicitly requested.
- Legacy `ai_files/` directories should be migrated to `.ai/` only during project init or when asked.
