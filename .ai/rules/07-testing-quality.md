# 07 Testing and Quality

## Default mode: fast mode

- Run targeted checks first.
- Do not run full suites by default.
- For backend behavior, run relevant Pest tests only, not the full suite by default.
- Run full suites only when explicitly requested or when risk justifies it.
- If checks fail, fix and rerun the relevant check.
- If fixing requires business/user input, stop and ask.

## Behavior changes

Every behavior change needs verification.

Prefer automated tests when practical:

- backend/business rules: Pest feature/unit tests
- browser-visible flows: Pest Browser or Playwright when coverage exists or is practical
- UI-only simple visual changes: concise manual verification steps may be enough unless regression risk is high

Do not create throwaway verification scripts or tinker flows when tests cover the behavior.

## Pest

- Use Pest for PHP tests.
- Use `php artisan make:test --pest {Name}` for new Pest tests.
- Most app behavior tests should be feature tests.
- Unit tests are appropriate for isolated logic.
- Do not remove tests or test files without approval.
- Tests should cover:
  - happy path
  - failure path
  - weird/edge path
- Use datasets for repetitive validation tests.
- Use specific response assertions:
  - `assertForbidden()`
  - `assertNotFound()`
  - `assertRedirect()`
  - `assertUnprocessable()`
  - etc.
- Avoid generic `assertStatus(403)` when a specific assertion exists.
- When mocking with Pest helper functions, import the helper explicitly, e.g. `use function Pest\Laravel\mock;`, unless existing tests use `$this->mock()`.

## Browser/UI testing

- Browser tests should live in `tests/Browser/` when using Pest 4 browser tests.
- For browser-visible behavior, prefer automated browser verification when coverage exists or is practical.
- Use backend tests for backend/business rules.
- Use DevTools/browser logs only for deeper debugging.
- Include concise manual UI verification steps in handoff for UI-verifiable behavior.

## Running tests

Use the smallest relevant check:

```bash
php artisan test --compact tests/Feature/ExampleTest.php
php artisan test --compact --filter=testName
```

Do not run the full Pest suite by default.

## Type/static/build checks

Run checks based on files changed and risk:

- PHP formatting: `vendor/bin/pint --dirty`
- PHP static analysis: Larastan/PHPStan if configured and relevant
- JS/TS typecheck: `vue-tsc` if configured and frontend TypeScript changed
- Lint: if configured and relevant
- Production build: if frontend/build-impacting changes were made and handoff/finalization requires it

## Pint

- Run `vendor/bin/pint --dirty` before handoff/finalization when PHP files were edited.
- Run Pint earlier if formatting is needed for the current slice.
- Do not run full Pint repeatedly during tiny iterations.
- Do not use `pint --test` as the primary formatter command unless specifically checking formatting without changes.

## Handoff quality

When finishing a slice, include:

- changed files
- key behavior implemented
- checks run and results
- relevant failures and fixes
- manual UI verification steps when applicable
- next recommended step
