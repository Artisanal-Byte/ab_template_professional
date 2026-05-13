# 08 Laravel Boost Tools

Use this file only when Laravel Boost is available.

## General

- Use Laravel Boost tools when available.
- Use `search-docs` before Laravel/Inertia/Vue/Wayfinder/Tailwind/Pest implementation choices.
- Use version-specific docs from Boost before falling back to memory or generic web docs.
- Use broad simple queries first, then refine.
- Do not add package names to search queries; Boost already knows installed packages.
- Use multiple simple queries when useful.

## Artisan

- Use `list-artisan-commands` before unfamiliar Artisan command flags.
- Use `php artisan make:* --no-interaction` for scaffolding.
- Pass correct options for the intended file type.

## URLs

- Use `get-absolute-url` whenever sharing a project URL with the user.

## Debugging

- Use `tinker` for PHP/model debugging when execution is needed.
- Use `database-query` for read-only SQL/database checks.
- Do not use tinker as a substitute for proper tests when tests should cover the behavior.
- Do not create verification scripts when tests are appropriate.

## Browser logs

- Use `browser-logs` for browser errors/exceptions when debugging frontend behavior.
- Only recent browser logs are useful.
- Ignore old logs unless they clearly map to the current issue.

## Laravel Herd

If the project is served by Laravel Herd:

- The app should already be available at a `.test` domain.
- Do not run commands just to make the site available over HTTP/S.
- Use `get-absolute-url` to generate correct local URLs.

## Documentation search compact rule

Search docs when making framework/package implementation choices for:

- Laravel
- Inertia
- Vue
- Wayfinder
- Tailwind
- Pest
- Fortify
- Laravel MCP/Boost
- other Laravel ecosystem packages

Keep docs output usage targeted. Do not paste long docs into responses unless requested.
