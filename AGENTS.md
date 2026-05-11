<laravel-boost-guidelines>
=== foundation ===

# Laravel Boost Rules (Compressed)

## Stack context (must respect)
- PHP 8.4+, Laravel 12, Inertia v2, Vue 3, Tailwind v4, Wayfinder, Fortify, Pest 4, Pint, Larastan/PHPStan.

## Response style
- Use caveman full by default: no filler, exact technical meaning, unchanged code blocks.
- Code/commit/PR text uses normal style.
- Switch only with `$caveman lite` / `$caveman ultra`; stop only with `stop caveman` / `normal mode`.

## Token/context policy
- `AGENTS.md` is active source of truth; `rules.md` is legacy pointer only.
- Keep context lean: targeted reads, no repeated large reads, concise logs unless full output requested.
- Nudge new chat when clean handoff is possible.
- Before starting a new moving-ahead slice, commit prior uncommitted work first; skip this only when the new slice fixes the immediately previous slice.
- If `plan.md` exists and no wiki context exists, bootstrap compressed `ai_files/`: `ai_index`, `state_now`, `02_tasks`, `07_decision_log`, `08_session_handoff`, contracts, archive.
- Load docs in order: `state_now`/`ai_index` -> contracts/active files -> archive on demand.
- Use subagents only when parallel gain is real.
- Keep handoff compact: changed files, behavior, checks, manual steps.

## Core conventions
- Follow existing project patterns first (file shape, naming, architecture).
- Prefer reuse over new components/primitives.
- Keep structure stable; no new base folders/dependencies without approval.
- Be concise in replies.
- Create docs only when explicitly asked.
- When `rtk` is installed, prefer `rtk` wrappers for shell reads/search/git/test output to reduce tokens.

=== boost tools ===

## Boost tool usage
- Use Laravel Boost tools when available.
- Use `search-docs` before Laravel/Inertia/Vue/Wayfinder/Tailwind/Pest implementation choices.
- Use broad simple search queries first; refine after.
- Use `list-artisan-commands` before unfamiliar artisan flags.
- Use `get-absolute-url` when sharing URLs.
- Use `tinker` for PHP/model debugging; `database-query` for read-only SQL checks.
- Use recent `browser-logs` only.

=== php ===

## PHP
- Always use braces for control blocks.
- Use constructor property promotion where appropriate.
- No empty public constructors.
- Require explicit param/return types.
- Prefer PHPDoc for non-obvious contracts/array shapes.
- Avoid inline comments unless logic is truly complex.
- Enum keys should be TitleCase when defining new enums.

=== laravel ===

## Laravel app rules
- Use `php artisan make:* --no-interaction` for scaffolding.
- Prefer Form Requests for validation.
- Keep controllers thin; domain/services hold business logic.
- Use transactions for multi-write operations.
- Prefer Eloquent relationships/scopes over raw SQL; avoid `DB::` unless necessary.
- Prevent N+1 with eager loading.
- Use policies/gates for authz.
- Use `route()` for links.
- Use `config()` not `env()` outside config files.

## Laravel 12 specifics
- Middleware/routing wiring is in `bootstrap/app.php`.
- `bootstrap/providers.php` for providers.
- No `app/Http/Kernel.php` / no legacy console kernel wiring.
- When altering columns in migrations, restate full column definition.

=== inertia/vue ===

## Inertia + Vue
- Inertia pages live in `resources/js/Pages` unless project config says otherwise.
- Use `Inertia::render()` server-side.
- Use `<Link>` / `router.visit()` for navigation.
- Vue SFCs must have single root.

## Inertia v2 behavior
- Prefer v2 patterns where useful: deferred props, merge/infinite scroll, polling, prefetch.
- Forms: prefer `<Form>` or `useForm` per existing page pattern.

=== wayfinder ===

## Wayfinder
- Prefer named imports (tree-shaking).
- Avoid default controller imports.
- Regenerate wayfinder types after route changes when needed.

=== tests ===

## Testing policy
- Every behavior change needs automated test coverage.
- Use Pest for tests.
- Do not remove tests without approval.
- Run only relevant Pest tests for changed behavior, and only when genuinely needed.
- Run only relevant Playwright tests for changed UI/browser behavior, and only when genuinely needed.
- Do not run full Pest or Playwright suites by default.
- Run typecheck, Pint, and PHPStan/Larastan only when session handoff is requested, unless needed to debug the current change.
- Use specific response assertions (`assertForbidden`, `assertNotFound`, etc.).
- Use datasets for repetitive validation tests.
- Add concise manual UI verification steps when behavior is UI-verifiable.

=== formatting ===

## Formatting
- Run `vendor/bin/pint --dirty` only when session handoff is requested, unless formatting is needed for the current slice.

=== tailwind ===

## Tailwind v4
- Use Tailwind utilities; avoid ad-hoc CSS unless needed.
- Respect existing visual language/design system.
- Use gap utilities for list spacing.
- Preserve dark mode behavior where existing pages support it.
- Use Tailwind v4 utilities only (no deprecated v3 utilities).
- When changing shared design-system/components, add a short reusable template prompt/rule to `design_system_chenges.md`.
- Inputs, selects, search-select triggers, and comparable form controls must share the same default control height at component level.

</laravel-boost-guidelines>
