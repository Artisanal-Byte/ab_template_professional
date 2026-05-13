# 04 Laravel

## Laravel conventions

- Use `php artisan make:* --no-interaction` for scaffolding.
- Use `list-artisan-commands` before unfamiliar Artisan flags when Boost is available.
- If creating a generic PHP class, use `php artisan make:class` unless project convention differs.
- Follow existing directory structure.
- Do not create new base folders without approval.

## Laravel 13 structure

- Middleware/routing wiring belongs in `bootstrap/app.php`.
- Providers are listed in `bootstrap/providers.php`.
- Do not assume `app/Http/Kernel.php` exists.
- Do not assume legacy console kernel registration.
- Console commands in `app/Console/Commands/` are automatically available.

## Controllers and actions

- Keep controllers thin.
- Controllers orchestrate request/response flow.
- Domain/services/actions hold business logic.
- Follow the project's existing action/service pattern.
- Prefer single-action/invokable controllers when existing project convention supports it.
- Do not place complex business logic directly inside controllers.

## Validation

- Prefer Form Requests for validation.
- Include rules and custom messages when useful.
- Check sibling Form Requests for array-vs-string rule style.
- Keep validation behavior covered by relevant Pest tests.

## Authorization

- Use policies/gates/permissions for authorization logic.
- Prefer explicit authorization checks.
- Do not hide authorization decisions in UI-only logic.
- Sensitive permission logic needs tests.

## Database / Eloquent

- Prefer Eloquent relationships/scopes over raw SQL.
- Use relationship methods with return type hints.
- Prefer `Model::query()` over `DB::` unless raw/query-builder code is necessary.
- Use Laravel query builder for genuinely complex database operations.
- Prevent N+1 with eager loading.
- Use transactions for multi-write operations.
- Use queued jobs with `ShouldQueue` for time-consuming work.
- Use `config()` instead of `env()` outside config files.
- Use named routes and `route()` for URL generation.

## PostgreSQL

- Prefer correct column types:
  - `uuid` where identifiers need UUIDs
  - `jsonb` for queryable structured JSON
  - `timestamptz`/timezone-aware dates where appropriate
  - proper numeric types for money/counts
- Add indexes for real query paths:
  - foreign keys
  - lookup columns
  - unique constraints
  - common filters/sorts
- Prefer descriptive table and column names, even if long.
- Keep migrations reversible when feasible.
- Prefer additive migrations over destructive migrations.
- When altering columns, restate the full column definition so attributes are not lost.
- Do not drop columns/tables without explicit approval.

## Models

- Use factories in tests.
- Check existing factories for states before manually building records.
- Use the `casts()` method if that is the project convention.
- Prefer enums/value objects for constrained domain values when they improve clarity.
- When creating new models, create useful factories and seeders when relevant.

## APIs

- For APIs, default to Eloquent API Resources and API versioning unless existing API routes follow a different convention.
- Keep response shapes consistent with existing resources.
- Avoid leaking internal model structure accidentally.

## Vite / frontend build errors

If a user cannot see frontend changes or a Vite manifest error appears:

- ask whether `npm run dev`, `npm run build`, or `composer run dev` is running
- run/ask for the relevant command based on repo workflow
- do not invent unrelated backend fixes for asset issues
