# AB Template Professional

Reusable Laravel 13 + Inertia v3 + Vue 3 + Tailwind CSS v4 starter template for building full-stack web applications with AI coding agents.

## Stack

| Layer | Technology |
|---|---|
| PHP | 8.4+ |
| Framework | Laravel 13+ |
| SPA Bridge | Inertia.js for Laravel v3 |
| Frontend Framework | Vue 3.5+ (Composition API + `<script setup lang="ts">`) |
| CSS Framework | Tailwind CSS v4 |
| Route Helpers | Wayfinder |
| Auth | Fortify |
| Testing | Pest 4, Pint, Larastan, ESLint, Prettier, vue-tsc |
| Rich Text | TipTap |
| UI Primitives | Reka UI |
| Build | Vite |
| JS Package Manager | Bun (preferred) |

## What's included

- **Auth scaffolding** — Login, Register, Password reset, Email verification, Two-factor authentication
- **Settings pages** — Profile, Password, Appearance (theme), Two-factor
- **40+ UI components** — Buttons, inputs, selects, dialogs, tables, cards, badges, alerts, avatars, tooltips, rich text editor, datetime picker, currency input, OTP input, and more
- **6 layout variants** — AppHeaderLayout, AppSidebarLayout, AuthCardLayout, AuthSplitLayout, AuthSimpleLayout, Settings Layout
- **Dark mode** — OKLCH-based CSS custom property token system with light/dark themes
- **Composables** — Appearance toggle, currency locale, initials, rich text editor, select display, two-factor auth, datetime picker
- **Playgrounds** — Dev-only surfaces for testing all UI components (disabled in production)
- **SSR support** — Vite SSR setup included

## AI Rule System

This template ships with a portable, harness-neutral AI rule system so any coding agent (Codex, Roo Code, Cline, OpenCode, Kilo, Aider, etc.) can work with it consistently.

### Rule files

| File | Purpose |
|---|---|
| `AGENTS.md` | Canonical entrypoint — read this first |
| `.ai/rules/00-project-init.md` | Project/spec initialization |
| `.ai/rules/01-operating-mode.md` | Work style, safety, context control |
| `.ai/rules/02-project-context.md` | Stack, source hierarchy, template-aware behavior |
| `.ai/rules/03-coding-standards.md` | Naming, style, PHP/JS/Vue conventions |
| `.ai/rules/04-laravel.md` | Laravel 13 conventions, PostgreSQL, Eloquent |
| `.ai/rules/05-inertia-vue-wayfinder.md` | Inertia, Vue SFC, Wayfinder patterns |
| `.ai/rules/06-tailwind-ui.md` | Tailwind v4, design system guardrails |
| `.ai/rules/07-testing-quality.md` | Pest, fast-mode testing, handoff quality |
| `.ai/rules/08-boost-tools.md` | Laravel Boost tool usage |
| `.ai/rules/09-git-handoff.md` | Git boundaries, commit style, handoff format |
| `.roo/rules/00-load-agents.md` | Roo Code specific loader |

### How agents use rules

Agents load `AGENTS.md` first, then selectively load only the rule files relevant to the current task (per the rule loading map in `AGENTS.md`). They do NOT load every rule file by default.

### Template documentation

The `.ai/template/*` directory documents the inherited template, design system, reusable components, layout conventions, and usage rules. **This is not active project state.**

- `.ai/template/profile.md` — Template identity, stack, structure, conventions
- `.ai/template/design-system.md` — Visual language, tokens, form controls, patterns
- `.ai/template/component-inventory.md` — Full reusable component inventory
- `.ai/template/usage-rules.md` — Agent-facing rules for template usage
- `.ai/template/change-log.md` — Template-level changelog

Cloned app projects should treat these as canonical documentation for UI/design-system/component/layout decisions. Do not use as app requirements, active specs, project decisions, or domain memory.

### Template updates tracker

Each cloned app project inherits `.ai/template_updates.md` for recording reusable improvements discovered during development. These are candidates for manual porting back to the template repo.

**Note:** `.ai/template_updates.md` is for app projects only. Template-repo maintenance changes go in `.ai/template/change-log.md`.

## Getting started

### Prerequisites

- PHP 8.4+
- Composer
- Bun (preferred) or npm/yarn
- PostgreSQL
- Git

### Step 1 — Clone the template

```bash
git clone https://github.com/Artisanal-Byte/ab_template_professional.git my-app
cd my-app
```

### Step 2 — Clean up template-specific files

Delete files that only belong in the template repo:

```bash
rm TEMPLATE_REPO_MAINTENANCE.md
```

(The `.ai/` directory and rule files should be kept — they help AI agents work with the project.)

### Step 3 — Set up the environment

```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Install PHP dependencies
composer install

# Install JS dependencies
bun install

# Run migrations
php artisan migrate --force
```

### Step 4 — Start development

```bash
# Dev server (PHP + Queue + Vite concurrently)
composer run dev

# Or individually:
php artisan serve
bun run dev
```

Visit `http://localhost:8000` in your browser.

## Starting a new project with AI

This template uses a **spec-driven development (SDD)** workflow. Your AI agent reads `AGENTS.md` and selectively loads rule files, then works from structured specs under `.ai/`.

### Before you begin

- Clone the template and clean up (see [Getting started](#getting-started)).
- Decide whether you have a `plan.md` (product plan) or not.
- Open your project in an AI-capable IDE (VS Code with Roo Code, Cursor, Claude Desktop, etc.).

### Scenario A — You have a `plan.md`

Place `plan.md` at the repo root, then give the agent this prompt:

```
Read AGENTS.md first. Then read .ai/rules/00-project-init.md, .ai/rules/01-operating-mode.md, and .ai/rules/09-git-handoff.md.

I have a plan.md. Initialize the project: extract specs from it, ask blocking questions, confirm assumptions, then create .ai/project/* and .ai/specs/* structure.

Read .ai/template/* before generating specs so you reuse existing template components and layouts.
```

The agent will:
1. Classify the project state.
2. Read `.ai/template/*` to understand what the template already provides.
3. Extract requirements from `plan.md`.
4. Ask blocking questions (architecture, domain logic, permissions, template usage).
5. List proceedable assumptions.
6. After approval, create `.ai/project/*` and `.ai/specs/*` structure.

### Scenario B — You do not have a `plan.md`

If you do not have a product plan yet, start by helping the agent understand your vision:

```
Read AGENTS.md first. Then read .ai/rules/00-project-init.md and .ai/rules/01-operating-mode.md.

I do not have a plan.md yet. Help me create one. Here is what I want to build: [describe your idea]

Read .ai/template/* first so you understand the template's existing components and can suggest what is already available.
```

The agent will:
1. Read `.ai/template/*` to understand existing template capabilities.
2. Ask clarifying questions about your idea.
3. Help you draft a `plan.md` with product purpose, core modules, workflows, constraints, and milestones.
4. Once `plan.md` exists, follow the Scenario A flow to generate specs.

### Executing an existing spec packet

If specs already exist (e.g., from a previous session), tell the agent:

```
Read AGENTS.md first. Then read .ai/rules/01-operating-mode.md, .ai/rules/02-project-context.md, .ai/rules/03-coding-standards.md, .ai/rules/07-testing-quality.md, and .ai/rules/09-git-handoff.md.

Execute the spec at .ai/specs/NNN-feature-name/. Read the active spec files, then implement the tasks.

Read .ai/template/* before creating any new UI components — prefer existing template components.
```

The agent will:
1. Load the active spec packet.
2. Read only the relevant source files.
3. Implement tasks in increments.
4. Run targeted tests.
5. Commit before starting a new independent slice.
6. Provide a handoff summary.

### Using .ai/template/* during spec creation

When the agent generates specs or implements features:
- It must read `.ai/template/profile.md` to understand the stack and conventions.
- It must read `.ai/template/component-inventory.md` before creating any new UI component.
- It must read `.ai/template/design-system.md` before changing layouts, form controls, or shared UI.
- It must reference existing template components/layouts in generated specs instead of inventing new UI.

### Recording reusable improvements

When making changes that could benefit the template repo (shared layouts, design-system primitives, form controls, reusable utilities, testing conventions):

1. Record the change in `.ai/template_updates.md` (in the app project).
2. Include: change type, files changed, reusable value, porting notes, checks run.
3. These entries are candidates for manual porting back to the template repo.

**Note:** `.ai/template_updates.md` is for app projects only. Template-repo maintenance changes go in `.ai/template/change-log.md`.

## Project structure

```
app/
  Http/Controllers/     # Request handlers
  Models/                # Eloquent models
  Providers/             # Service providers
resources/js/
  components/
    ui/                  # Design-system primitives (40+ components)
    playgrounds/         # Dev-only playground surfaces
    App*.vue             # Layout shell components
    Nav*.vue             # Navigation components
  composables/           # Vue composables
  layouts/               # Page layout wrappers
  pages/                 # Inertia page components
  lib/                   # Utilities
routes/
  web.php                # Main routes
  settings.php           # Settings routes
tests/                   # Pest tests
```

## Design system

All colors use OKLCH-based CSS custom properties defined in `resources/css/tokens.css`. The preset maps these to Tailwind classes in `tailwind.preset.ts`.

### Control heights

| Size | Height | Usage |
|---|---|---|
| sm | h-8 | Compact forms |
| md | h-10 | Default |
| lg | h-11 | Prominent forms |

### Button variants

primary, secondary, outline, ghost, success, info, warning, destructive, link

### Dark mode

Toggle via the `.dark` class. Use `useAppearance()` composable for state management. All colors have light/dark tokens.

## Maintenance

For template repo maintenance instructions, see `TEMPLATE_REPO_MAINTENANCE.md` (template repo only, delete from cloned apps).

For remaining template work candidates, see `.ai/specs/template-backlog.md`.
