# 02 Project Context

## Default app stack

Unless the repo proves otherwise, assume:

- PHP 8.4+
- Laravel 12
- Inertia v2
- Vue 3
- Tailwind CSS v4
- Wayfinder
- Fortify when auth scaffolding exists
- Pest 4
- Pint
- Larastan/PHPStan
- PostgreSQL
- Bun preferred for JS/Node package management

Do not preserve exact patch versions in rules. Verify actual versions from `composer.json`, `package.json`, lockfiles, or Boost tools when precision matters.

## Source of truth

Use this hierarchy:

1. User's latest instruction.
2. Active feature spec under `.ai/specs/*`.
3. `.ai/state_now.md`.
4. `.ai/project/*`.
5. `.ai/contracts/*`.
6. Existing implementation.
7. `plan.md`.
8. Archived notes.

Do not load archived files unless needed.

## Project memory

Use `.ai/index.md` as the loading map when present.

Expected structure:

```text
.ai/
  index.md
  state_now.md
  decision_log.md
  session_handoff.md
  template_updates.md

  template/
    profile.md
    design-system.md
    component-inventory.md
    usage-rules.md

  project/
    brief.md
    architecture.md
    domain_model.md
    design_system.md
    test_strategy.md

  specs/
    NNN-feature-name/
      requirements.md
      design.md
      tasks.md
      acceptance.md
      handoff.md

  contracts/
  archive/
```

If the repo uses legacy `ai_files/`, do not migrate it unless asked or during project init.

## Template-aware behavior

If `.ai/template/*` exists:

- Treat it as canonical template documentation.
- Read it before changing layouts, primitives, form controls, navigation, tables, dialogs, or shared UI.
- Do not regenerate template profile per project.
- Verify template docs lightly if they appear stale.
- Ask before updating template docs.

If `.ai/template/*` does not exist but the repo appears template-based, mention that a template profile may be useful.

## Template update tracking in app projects

When working inside an app project cloned from a template, create and maintain `.ai/template_updates.md`.

Purpose:

- record app-project changes that may be useful to port back into the template repo later
- separate reusable template improvements from app-specific business features
- prevent template-worthy improvements from being lost inside normal feature work

Create `.ai/template_updates.md` when:

- the repo appears template-based
- `.ai/template/*` exists
- a change touches reusable/template-like areas
- the user explicitly asks to track template updates

Track changes when modifying:

- shared layouts
- app shell/navigation
- theme tokens
- design-system primitives
- form controls
- dialogs/modals/drawers
- tables/lists/cards
- reusable helpers/utilities
- shared validation/display patterns
- route/page conventions inherited from the template
- testing/build conventions inherited from the template

Do not track purely app-specific work such as:

- client/domain modules
- business-specific pages
- feature-specific copy
- app-specific database tables
- one-off UI built only for a business flow
- temporary hacks

Use this format:

```md
# Template Updates

Reusable improvements discovered during app development.

These are candidates to review and manually port back to the template repo.

## Pending review

### YYYY-MM-DD — Short title

**Status:** candidate | accepted-for-template | rejected | already-ported

**Change type:** component | layout | design-system | utility | convention | testing | build | other

**App context**
- Why this change was made in the app project.

**Reusable value**
- Why this may belong in the template.

**Files changed**
- `path/to/file`

**Porting notes**
- What to copy/adapt in the template repo.
- Any app-specific parts to exclude.

**Checks**
- Commands/manual checks run.

**Decision**
- Pending / accepted / rejected, with reason.
```

When updating `.ai/session_handoff.md`, mention any new or changed `.ai/template_updates.md` entries.

Do not automatically port changes back to the template repo from an app project. Only record candidates unless the user explicitly asks to update the template repo.

## Contract files

Contracts define invariants that must not break.

Load contract files only when relevant to the active module.

If implementation conflicts with a contract:

1. Stop.
2. Explain the conflict.
3. Ask for confirmation before changing behavior.

## Decision log

Use `.ai/decision_log.md` for durable decisions.

Log decisions that affect:

- data model
- permissions
- architecture
- external integrations
- UI/design-system primitives
- irreversible migrations
- security/compliance behavior

Do not log trivial implementation details.
