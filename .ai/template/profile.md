# AB Template Profile

## Template name

Artisanal Byte (AB) Template

## Purpose

Reusable Laravel/Inertia/Vue/Tailwind starter template for building full-stack web applications with AI coding agents.

## Supported project type

Laravel 13+ Inertia SPA/SWR applications with Vue 3 frontend.

## Stack

| Layer | Technology |
|---|---|
| PHP | 8.4+ |
| Framework | Laravel 13+ (`^13.1`) |
| SPA Bridge | Inertia.js v3 |
| Backend Inertia Adapter | `inertiajs/inertia-laravel 3.0.6` |
| Frontend Inertia Adapter | `@inertiajs/vue3 3.1.1` |
| Frontend Framework | Vue 3 (`^3.5.34`) |
| CSS Framework | Tailwind CSS v4 (`^4.3.0`) |
| Route Helpers | Wayfinder (`^0.1.14`) |
| Auth | Fortify (`^1.36`) |
| Testing (Backend) | Pest 4 (`^4.4`) + PHPUnit 12 |
| Testing (Lint) | Pint, Larastan, ESLint, Prettier |
| Type Checking | TypeScript + vue-tsc |
| Rich Text | TipTap (`^3.23.1`) |
| UI Primitives | Reka UI (`^2.9.7`) |
| Build | Vite (`^8.0.12`) |
| JS Package Manager | Bun (preferred) |

## Auth / scaffolding

Fortify provides:
- Login, Register, Forgot Password, Reset Password
- Email verification
- Two-factor authentication (setup, recovery codes)
- Password management
- Session management

Pages live in `resources/js/pages/auth/`.
Controllers live in `app/Http/Responses/` (custom response classes) and Fortify's built-in actions.

## Frontend structure

```
resources/js/
  components/
    ui/              # Design-system primitives (40+ components)
    playgrounds/     # Dev-only playground surfaces
    App*.vue         # Layout shell components
    Nav*.vue         # Navigation components
    *.vue            # Shared non-UI components
  composables/       # Vue composables
  config/            # Static config (navConfig)
  lib/               # Utilities (cn, attrs, uiClass, richText, utils)
  layouts/           # Page layout wrappers
  pages/             # Inertia page components
  routes/            # Wayfinder-generated route helpers
  types/             # TypeScript type definitions
```

## Backend structure

```
app/
  Actions/Fortify/   # Fortify action overrides
  Http/
    Controllers/     # Request handlers
    Middleware/      # Request middleware
    Requests/        # Form request validation
    Responses/       # Custom Fortify response classes
  Models/            # Eloquent models
  Providers/         # Service providers
bootstrap/
  app.php            # Application bootstrap + middleware wiring
  providers.php      # Service provider registration
config/              # Configuration files
routes/
  web.php            # Main routes
  settings.php       # Settings routes (auth-protected)
  console.php        # Console routes
database/
  migrations/        # Database migrations
  factories/         # Model factories
  seeders/           # Database seeders
```

## Routing / page conventions

- Internal navigation uses Inertia `<Link>` (no traditional anchors).
- Settings routes are in a separate `routes/settings.php` file, middleware-protected.
- Playground/design-system routes are disabled in production (`app()->isProduction()`).
- Wayfinder generates typed route helpers in `resources/js/routes/`.

## Design system summary

- OKLCH-based CSS custom property tokens in `resources/css/tokens.css`.
- Light/dark mode via `.dark` class toggle.
- Semantic color palette: border, card, primary, secondary, accent, info, success, warning, error + derived tokens via `color-mix()`.
- Border radius: sm (2px), md (4px), lg (8px).
- Control heights: sm (h-8), md (h-10), lg (h-11).
- Button variants: primary, secondary, outline, ghost, success, info, warning, destructive, link.
- All form controls share consistent heights via `inputSizeClasses` in `uiClass.ts`.

## What the template already provides

- Complete auth scaffolding (login, register, password, 2FA, email verification)
- 6 layout variants (AppHeaderLayout, AppSidebarLayout, AuthCardLayout, AuthSplitLayout, AuthSimpleLayout, settings/Layout)
- 40+ UI components (buttons, inputs, selects, dialogs, tables, cards, badges, alerts, avatars, tooltips, rich text editor, datetime picker, currency input, OTP input, etc.)
- Dark mode with OKLCH token system
- Settings pages (profile, password, appearance, two-factor)
- Dashboard page
- Composables (appearance, currency locale, initials, rich text editor, select display, two-factor auth, datetime picker)
- Utilities (cn, attrs splitting, uiClass builders, richText form builder)
- Pest testing setup with feature tests
- ESLint + Prettier + vue-tsc + Pint + Larastan
- Playgrounds for all UI components (dev-only)
- SSR setup (`resources/js/ssr.ts`)

## What cloned app projects are expected to add

- Business domain models, controllers, migrations
- Feature-specific pages and routes
- App-specific navigation configuration (`navConfig.ts`)
- App-specific policies/gates/permissions
- Feature-specific tests
- Business-specific copy and content
- Any additional dependencies needed for the domain

## What should not be changed casually

- Token system (`tokens.css`) — changing token names or structure breaks the entire design system.
- Component API of `ui/` primitives — changing props/events breaks all consumers.
- Layout conventions — changing layout structure breaks page expectations.
- Form-control default heights — inconsistency causes visual drift.
- Dark mode behavior — breaking it affects all pages.
- Navigation config pattern (`navConfig.ts`) — changing it breaks sidebar/header nav.
