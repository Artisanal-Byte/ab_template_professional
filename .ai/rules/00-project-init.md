# 00 Project Init

Use this file only when initializing or restructuring a project for spec-driven development.

Do not load this file during normal implementation unless the user explicitly asks for project/spec initialization.

## Trigger conditions

Use project init when any of these are true:

- User says to initialize/bootstrap the project.
- `plan.md` exists but `.ai/` project/spec context does not.
- The repo has code but no clear `.ai/index.md`, `.ai/state_now.md`, or spec system.
- User asks to convert a plan into executable specs.
- User asks to make the repo ready for AI execution.
- A template repo has been cloned and needs to be understood.

## No plan.md handling

If no `plan.md` exists:

1. Do NOT create specs from vague requirements.
2. Ask the user whether to help create `plan.md` first.
3. If the user wants to proceed without `plan.md`, ask clarifying questions about:
   - Product purpose
   - Target users and roles
   - Core modules and workflows
   - Non-negotiable constraints
4. Help the user draft a `plan.md` based on the discussion.
5. Once `plan.md` exists, follow the normal init process.

For cloned template apps, always read `.ai/template/*` before generating project specs so specs reference existing template components instead of inventing new UI.

## Non-negotiable during init

- Do not implement application code during project init.
- Do not create migrations, controllers, pages, components, or tests during init.
- Only inspect, ask questions, and create/update planning artifacts.
- Do not overwrite existing `.ai/` files without first reading them and explaining the change.
- If the repo already has useful AI context, preserve it and migrate carefully.

## Source hierarchy

Before init:

1. `plan.md` is the main planning input.
2. Existing repo code is implementation evidence.
3. Existing `.ai/` or `ai_files/` docs are memory/context evidence.

After init approval:

1. `.ai/project/*`, `.ai/specs/*`, `.ai/contracts/*`, `.ai/state_now.md`, and `.ai/decision_log.md` become the execution source of truth.
2. `plan.md` remains the upstream product plan.
3. Do not repeatedly load full `plan.md` during implementation. Load active spec packets instead.
4. Reload `plan.md` only when checking drift, resolving ambiguity, or updating specs.

## Init process

### 1. Read and analyze

Read `AGENTS.md`, this file, and `plan.md`.

Inspect the repo lightly:

- `composer.json`
- `package.json`
- route files
- `resources/js` structure
- `app` structure
- migrations
- existing tests
- existing `.ai/` or `ai_files/`
- existing design-system/component folders

Do not perform broad exploratory scans unless the structure is unclear.

### 2. Classify project state

Classify the repo as one of:

- blank project
- Laravel starter/scaffold
- template-based project
- brownfield/existing product
- legacy project being converted to SDD

State the classification in the init notes.

### 3. Template detection

If the project appears based on a template repo:

1. Check for `.ai/template/profile.md`, `.ai/template/design-system.md`, `.ai/template/component-inventory.md`, and `.ai/template/usage-rules.md`.
2. If present, treat them as canonical template documentation.
3. Verify them lightly against the repo structure.
4. If mismatches are found, report them and ask whether to update the template docs.
5. If missing, inspect template structure and propose creating template docs.
6. Do not regenerate template docs per project when canonical template docs already exist.
7. Do not create or update template docs without confirmation unless the user explicitly requested template profiling.

Template profile generation/maintenance belongs in the template repo itself when possible.

### 4. Extract from plan

Extract and classify:

- product purpose
- target users and roles
- core modules
- workflows
- business rules
- non-negotiable constraints
- technical stack assumptions
- data/domain entities
- permissions/security/compliance needs
- integrations
- UI/design requirements
- phases/milestones
- risks
- assumptions
- contradictions
- open questions
- deferred/future scope

Classify each important item as:

- confirmed
- assumption
- needs confirmation
- contradiction
- implementation detail
- future/deferred

### 5. Ask only useful questions

Before generating executable specs, ask blocking questions only.

Ask about:

- irreversible architecture decisions
- ambiguous domain logic
- security/data isolation
- permissions
- billing/payment logic
- external integration ownership
- template/design-system usage
- migration/data retention risks
- anything that affects multiple modules

Also list proceedable assumptions.

Format:

```md
## Blocking questions

1. [Architecture] ...
2. [Domain] ...
3. [Permissions] ...
4. [UI/Design System] ...

## Assumptions I can proceed with if approved

A1. ...
A2. ...
A3. ...
```

Do not ask dozens of low-value questions.

### 6. Create `.ai/` structure after approval

After the user approves assumptions or answers questions, create/update:

```text
.ai/
  index.md
  state_now.md
  decision_log.md
  session_handoff.md

  project/
    brief.md
    architecture.md
    domain_model.md
    design_system.md
    test_strategy.md

  specs/
    001-foundation/
      requirements.md
      design.md
      tasks.md
      acceptance.md
      handoff.md

  contracts/
    README.md

  archive/
    README.md
```

If a template profile exists, link to `.ai/template/*` from `.ai/index.md` and `.ai/project/design_system.md`.

### 7. Spec packet format

Each feature spec must be executable without rereading the whole plan.

Use:

```text
.ai/specs/NNN-feature-name/
  requirements.md
  design.md
  tasks.md
  acceptance.md
  handoff.md
```

### 8. State tracking

Use `.ai/state_now.md` for current execution state.

Use `.ai/decision_log.md` for durable decisions.

Use `.ai/session_handoff.md` for session-level handoff.

### 9. Contract files

Create `.ai/contracts/README.md` for invariants that must not break.

### 10. Archive

Use `.ai/archive/` for completed or superseded specs.

## Questions to ask before init

- Is this a template-based project?
- Does `.ai/template/*` already exist?
- Is there a `plan.md` to extract from?
- Are there existing `.ai/` or `ai_files/` to preserve?
- What is the project classification (blank, scaffold, template, brownfield, legacy)?
