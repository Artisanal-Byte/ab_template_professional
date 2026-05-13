# 01 Operating Mode

## Work style

- Work in increments.
- No giant one-shot full-app implementations.
- Keep diffs focused.
- Avoid unrelated refactors.
- Do not change behavior outside the requested scope.
- Prefer clear, boring, maintainable implementation over cleverness.
- Follow existing project patterns before introducing new ones.
- Prefer framework conventions over custom abstractions.
- Prefer reuse over new code.
- Do not introduce new dependencies unless clearly needed and approved.
- Do not create new base folders without approval.
- Do not create docs unless explicitly requested, except project-init/spec artifacts.

## Before coding

- Read the active task/spec/handoff first.
- Read only the relevant source files.
- Check sibling files for structure, naming, and style.
- Identify likely files to touch.
- If scope is ambiguous, ask a focused question or state safe assumptions.

## During coding

- Make one coherent change at a time.
- After each meaningful change, run relevant checks.
- Do not assume a change worked.
- Do not continue piling changes on top of failing checks unless debugging that failure.
- If a fix requires user/business input, stop and ask.

## Subagents

- Prefer one agent path by default.
- Use subagents only when parallel gain is real.
- Use subagents for independent research, isolated file groups, or review passes.
- Do not let subagents edit overlapping files unless explicitly planned.
- If overlap or unexpected cross-slice edits are detected, inspect and reconcile diffs before continuing.

## Context control

- Keep context lean.
- Avoid repeated large reads.
- Avoid unrelated exploratory scans when current scope is known.
- Avoid dumping full command output unless requested.
- When `rtk` is installed, prefer `rtk` wrappers for reads/search/git/test output.
- Nudge the user to start a new chat when a clean handoff is possible.

## Safety

- Do not run destructive commands that can delete data, wipe environments, reset history, or drop tables/columns unless explicitly approved.
- Prefer additive migrations over destructive migrations.
- Never modify `.env` secrets destructively.
- Never expose credentials, tokens, or private keys.
- Do not install packages globally unless explicitly approved.
