# AGENTS.md

Canonical portable rules for app projects.

These rules are harness-neutral and should be followed by Codex, Roo Code, Cline, OpenCode, Kilo, Aider, and other coding agents.

## Source of truth

- `AGENTS.md` is the root source of truth.
- `.ai/rules/*.md` contains modular rules. Load only the files relevant to the current task.
- Legacy files such as `rules.md`, `Agents(old).md`, or older generated Boost files are historical only unless the user explicitly says otherwise.
- Project-specific specs and state live under `.ai/`.
- If a project has legacy `ai_files/`, migrate or map it into `.ai/` only when asked or during project init.

## Default stack context

Assume this project type unless the repo proves otherwise:

- PHP 8.4+
- Laravel 12
- Inertia v2
- Vue 3
- Tailwind CSS v4
- Wayfinder
- Fortify where auth scaffolding exists
- Pest 4
- Pint
- Larastan/PHPStan
- PostgreSQL
- Bun preferred for JS/Node package management when a JS package manager is needed

## Rule loading map

Always read this file first.

Load modular rules only as needed:

| Task type | Load these files |
|---|---|
| New project/spec bootstrap | `.ai/rules/00-project-init.md`, `.ai/rules/01-operating-mode.md`, `.ai/rules/09-git-handoff.md` |
| General coding task | `.ai/rules/01-operating-mode.md`, `.ai/rules/02-project-context.md`, `.ai/rules/03-coding-standards.md`, `.ai/rules/07-testing-quality.md`, `.ai/rules/09-git-handoff.md` |
| Laravel/backend task | General coding files + `.ai/rules/04-laravel.md`, `.ai/rules/08-boost-tools.md` |
| Inertia/Vue/frontend task | General coding files + `.ai/rules/05-inertia-vue-wayfinder.md`, `.ai/rules/06-tailwind-ui.md`, `.ai/rules/08-boost-tools.md` |
| UI/design-system task | General coding files + `.ai/rules/05-inertia-vue-wayfinder.md`, `.ai/rules/06-tailwind-ui.md` |
| Testing/fix failing checks | `.ai/rules/01-operating-mode.md`, `.ai/rules/07-testing-quality.md`, plus domain-specific files |
| Handoff/finish slice | `.ai/rules/07-testing-quality.md`, `.ai/rules/09-git-handoff.md` |

Do not load every rule file by default.

## Core operating rules

- Work in increments. No giant one-shot full-app implementations.
- Keep diffs focused. Avoid unrelated refactors.
- Follow existing project patterns first: file shape, naming, architecture, components, tests, and UI conventions.
- Prefer reuse over new code/components/primitives.
- Do not create new base folders without approval.
- Do not introduce new dependencies unless clearly needed and approved. Prefer built-in framework features first.
- Do not create documentation files unless explicitly requested, except project-init/spec artifacts created under `.ai/`.
- Do not run destructive commands that can delete data, wipe environments, reset history, or drop columns/tables unless explicitly required and approved.
- If a fix requires user/business/product input, stop and ask instead of guessing.
- When `rtk` is installed, prefer `rtk` wrappers for shell reads/search/git/test output to reduce tokens.

## Design-system guardrail

Before adding any new primitive component/functionality not already present in the design system:

1. Check existing components and usage patterns.
2. Explain why existing primitives are insufficient.
3. Ask for confirmation.
4. Only then create the new primitive.

Do not duplicate existing primitives.

## Verification default: fast mode

- After each meaningful change, run the relevant check(s). Do not assume it worked.
- Run targeted tests/checks first.
- For backend behavior, run relevant Pest tests only, not the full suite by default.
- Do not run full Pest/Playwright suites by default.
- Run broader gates only when explicitly requested, during final handoff if appropriate, or when risk justifies it.
- If checks fail, fix and rerun the relevant checks.
- If fixing requires user/business input, stop and ask.

## Git boundary

- Commit before starting a new independent slice.
- Skip that only when the new slice fixes the immediately previous slice.
- Do not auto-push unless the user asks, project workflow requires it, or handoff instructions say to push.
- Commit messages must be meaningful.

## Response style

- Use caveman full by default in normal project responses:
  - no filler
  - exact technical meaning
  - concise explanation
  - unchanged code blocks
- Code, commit messages, PR text, documentation, and user-facing copy should use normal style.
- Switch only when user says `$caveman lite` or `$caveman ultra`.
- Stop only when user says `stop caveman` or `normal mode`.

## Context policy

- Keep context lean.
- Read only files needed for the current slice.
- Avoid rereading unchanged large files.
- Avoid dumping full command output unless requested.
- Prefer one agent path by default.
- Use subagents only when parallel gain is real.
- If subagent overlap or unexpected cross-slice edits are detected, inspect and reconcile those diffs before continuing.
- Nudge the user to start a new chat when a clean handoff is possible.
