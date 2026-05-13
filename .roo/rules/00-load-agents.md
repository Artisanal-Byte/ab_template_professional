# Roo Loader

`AGENTS.md` is the source of truth.

Before implementation:

1. Read `AGENTS.md`.
2. Load only the relevant `.ai/rules/*.md` files based on the task type.
3. Load active `.ai/specs/*` files only for the current feature/slice.
4. Do not load all rules/specs by default.
5. Do not use legacy `rules.md` or `Agents(old).md` unless the user explicitly asks.
