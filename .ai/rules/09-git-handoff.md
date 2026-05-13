# 09 Git and Handoff

## Git boundaries

- Commit before starting a new independent slice.
- Skip this only when the new slice fixes the immediately previous slice.
- Do not mix unrelated slices in one commit.
- Do not auto-push unless:
  - user asks
  - project workflow explicitly requires it
  - handoff instructions explicitly say to push
- Commit messages must be meaningful and specific.

## Before committing

Before a slice commit:

1. Inspect changed files.
2. Ensure diffs are focused.
3. Run relevant checks.
4. Fix failures or document why unresolved.
5. Ensure no secrets or accidental files are included.

## Commit message style

Use normal style, not caveman style.

Prefer:

```text
Implement rental status update flow
Fix customer import validation
Add policy tests for document approval
```

Avoid vague messages:

```text
changes
fix
updates
final
```

## Handoff format

Keep handoff compact.

Use:

```md
## Handoff

### Changed files
- `path/to/file`: what changed

### Behavior
- ...

### Checks
- `command`: passed/failed
- `command`: passed/failed

### Manual verification
- Step 1
- Step 2

### Notes / risks
- ...

### Next step
- ...
```

## Session handoff files

If `.ai/session_handoff.md` exists:

- Update it at the end of meaningful sessions.
- Keep it compact.
- Include only current status, changed files, checks, known issues, and next step.
- Move stale history to `.ai/archive/` when it grows too large.

If feature-specific `.ai/specs/*/handoff.md` exists:

- Update that file for feature-local progress.
- Keep global handoff high-level.

## New chat nudge

When a clean handoff exists and context is getting heavy, nudge the user to start a new chat and point them to the handoff file.
