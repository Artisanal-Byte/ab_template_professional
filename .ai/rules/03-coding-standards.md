# 03 Coding Standards

## Naming

- Variables, functions, methods: `camelCase`.
- Classes, interfaces, traits, enums: `PascalCase`.
- Files: follow framework conventions.
- Database table/column names: `snake_case`.
- Use descriptive names. Prefer `isRegisteredForDiscounts` over `discount`.

## General style

- Keep code boring and readable.
- Keep functions focused.
- Avoid unrelated cleanup.
- Avoid nested ternary operators.
- Single-condition ternary is fine.
- Avoid magic strings when enums/value objects improve clarity.
- Prefer existing helpers/utilities/components before creating new ones.

## PHP

- Use `declare(strict_types=1);` in PHP files where project convention supports it.
- Always use braces for control blocks.
- Use constructor property promotion where appropriate.
- Do not create empty public constructors.
- Use explicit parameter and return types.
- Use typed properties.
- Use PHPDoc only where it adds information:
  - array shapes
  - generics
  - complex contracts
  - model/query return types
  - non-obvious behavior
  - important dynamic properties
- Do not add redundant PHPDoc that only repeats native types.
- Prefer PHPDoc over inline comments.
- Avoid inline comments unless logic is truly complex.
- Enum case names should be `PascalCase`/TitleCase.

## JavaScript / TypeScript / Vue

- Use TypeScript in Vue SFCs: `<script setup lang="ts">` unless explicitly told otherwise.
- Prefer object-style declarations for:
  - `defineProps({ ... })`
  - `defineEmits({ ... })`
  - `defineModel({ ... })`
- Use type-based declarations only when object-style becomes awkward or types are complex.
- Avoid multiline functions inside template directives such as `@click`, `@change`, etc.
- Extract non-trivial logic to `<script>` and call it from the template.
- Single-line lambdas like `(x) => foo(x)` are acceptable.
- Add JS/Vue comments only where logic is non-trivial. Use multiline comments when they improve clarity.

## Imports

- Follow existing import ordering.
- Remove unused imports.
- Prefer named imports when the library/tool benefits from tree-shaking.
- Do not introduce aliases or barrel files unless the project already uses that pattern.

## Error handling

- Prefer explicit errors over silent failure.
- Do not swallow exceptions unless the existing pattern does so for a reason.
- Use validation and authorization failures intentionally, not as generic exceptions.

## Comments

- Comments should explain why, not restate what.
- Avoid generated-comment clutter.
- Do not add TODOs unless they are actionable and tied to current scope or explicitly requested.
