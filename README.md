# AB Template Professional

Reusable Laravel 12 + Inertia v3 + Vue 3 + Tailwind CSS v4 starter template for building full-stack web applications with AI coding agents.

## Stack

| Layer | Technology |
|---|---|
| PHP | 8.4+ |
| Framework | Laravel 12+ |
| SPA Bridge | Inertia.js for Laravel v3 |
| Frontend Framework | Vue 3 (Composition API + `<script setup lang="ts">`) |
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
| `.ai/rules/04-laravel.md` | Laravel 12 conventions, PostgreSQL, Eloquent |
| `.ai/rules/05-inertia-vue-wayfinder.md` | Inertia, Vue SFC, Wayfinder patterns |
| `.ai/rules/06-tailwind-ui.md` | Tailwind v4, design system guardrails |
| `.ai/rules/07-testing-quality.md` | Pest, fast-mode testing, handoff quality |
| `.ai/rules/08-boost-tools.md` | Laravel Boost tool usage |
| `.ai/rules/09-git-handoff.md` | Git boundaries, commit style, handoff format |
| `.roo/rules/00-load-agents.md` | Roo Code specific loader |

### How agents use rules

Agents load `AGENTS.md` first, then selectively load only the rule files relevant to the current task (per the rule loading map in `AGENTS.md`). They do NOT load every rule file by default.

### Template documentation

The `.ai/template/*` directory documents the reusable template:

- `.ai/template/profile.md` — Template identity, stack, structure, conventions
- `.ai/template/design-system.md` — Visual language, tokens, form controls, patterns
- `.ai/template/component-inventory.md` — Full reusable component inventory
- `.ai/template/usage-rules.md` — Agent-facing rules for template usage
- `.ai/template/change-log.md` — Template-level changelog

Cloned app projects should treat these as canonical documentation.

### Template updates tracker

Each cloned app project inherits `.ai/template_updates.md` for recording reusable improvements discovered during development. These are candidates for manual porting back to the template repo.

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

When you want an AI coding agent to build features in your cloned app project, follow these steps:

### Step 1 — Set up the AI agent

Open your project in an AI-capable IDE (VS Code with Roo Code, Cursor, Claude Desktop, etc.).

### Step 2 — Give the initial prompt

Copy and paste this prompt into your AI agent:

```
Read AGENTS.md first. Then read .ai/rules/01-operating-mode.md, .ai/rules/02-project-context.md, .ai/rules/03-coding-standards.md, .ai/rules/07-testing-quality.md, and .ai/rules/09-git-handoff.md.

I want to build: [describe your feature/requirement]

Work in increments. Commit before starting each new slice. Run targeted tests only. Follow the design system — check .ai/template/component-inventory.md before creating any new UI components.
```

### Step 3 — For UI/frontend work, add this:

```
Read .ai/template/design-system.md and .ai/template/component-inventory.md before creating any UI. Prefer existing components. Do not duplicate primitives.
```

### Step 4 — For Laravel/backend work, add this:

```
Read .ai/rules/04-laravel.md. Follow Laravel 12 conventions. Use Inertia for page responses. Write Pest tests for backend behavior.
```

### Example prompts

**Adding a new page:**

```
Read AGENTS.md and the relevant rule files. Create a new Inertia page at resources/js/pages/Products.vue with a route in routes/web.php. Use the AppSidebarLayout. Wire it to a ProductsController@index that returns Inertia::render('Products', [...]). Write a Pest feature test.
```

**Adding a form:**

```
Read .ai/template/component-inventory.md first. Create a product edit form using the existing Input, Select, and Button components from ui/. Use Inertia <Form> with Wayfinder helpers. Add a FormRequest for validation. Write Pest tests for happy path, failure path, and edge cases.
```

**Adding a table:**

```
Read .ai/template/design-system.md. Create a products listing page using the existing PaginatedTable component. Wire it to a controller that returns paginated data with sort/search support. Write a feature test.
```

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
