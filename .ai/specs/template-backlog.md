# AB Template Backlog

Candidate remaining template work. These items are not yet implemented and may need spec packets.

## Pending review

### 1. Notification / Toast system

**Status:** candidate

**Description:** Need a backend-wired notification system. Currently only `Alert.vue` exists (static/dismissible div). Need:

- Frontend: Toast component (slide-in notifications, auto-dismiss, stacking) + notification display page
- Backend: Notification model, migration, controller, API endpoints
- Integration: Fortify event listeners or Inertia events to push notifications
- Database: notifications table with user_id, type, message, data, read_at

**App context:** Full-stack apps need a way to show success/error/info messages to users after form submissions, API calls, etc.

**Reusable value:** Every cloned app project will need this.

**Questions:**
- Should notifications be stored in the database (persistent) or client-only (transient)?
- Should there be a notification badge/count in the header?
- Should this integrate with Laravel's built-in notification channel system?

---

### 2. Tab component

**Status:** candidate

**Description:** Need a reusable Tab/Tabs component following the design system patterns. Currently only `AppearanceTabs.vue` exists as a top-level component (not in `ui/`).

**Proposed structure:**
```
resources/js/components/ui/Tabs.vue          — Tabs root (Reka UI TabsRoot wrapper)
resources/js/components/ui/TabsList.vue      — Tabs list container
resources/js/components/ui/TabsTrigger.vue   — Tab trigger button
resources/js/components/ui/TabsContent.vue   — Tab content panel
```

**App context:** Settings pages, dashboard widgets, and many other pages need tabbed navigation.

**Reusable value:** High — tabs are a common UI pattern.

**Questions:**
- Should this follow Reka UI Tabs primitives (like shadcn-vue does)?
- Should tabs support vertical orientation?
- Should tabs support keyboard navigation (arrow keys)?

---

### 3. Standalone Pagination component

**Status:** candidate

**Description:** `PaginatedTable.vue` has built-in pagination controls (prev/next/page numbers + per-page selector). A standalone `<Pagination>` component would extract just the page navigation for non-table list pages (e.g., card-based listings, grid views).

**Difference from PaginatedTable:**
- `PaginatedTable` = full table + pagination + search + sort + per-page
- `<Pagination>` = page navigation only (prev, next, page numbers, per-page selector) — no table, no search, no sort

**App context:** Many pages need paginated lists that are not tables (e.g., card grids, list items).

**Reusable value:** Medium — depends on whether cloned apps need non-table pagination.

**Questions:**
- Should this be a separate component or extracted from PaginatedTable?
- Should it support the same props (paginationMeta, perPageOptions, showJumpToPage)?

---

### 4. Programmatic Breadcrumb component

**Status:** candidate

**Description:** `AppBreadcrumbs.vue` is used only inside `AppHeader.vue`. Breadcrumbs are passed as props from the page. A programmatic `<Breadcrumb>` component would allow breadcrumbs to be rendered outside the header (e.g., in a page body).

**App context:** Some pages may need breadcrumb trails displayed within the page content, not just in the header.

**Reusable value:** Low to medium — depends on how often this is needed.

**Questions:**
- Is this worth a separate component, or can breadcrumbs be passed to AppHeader?
- Should there be a `<Breadcrumb>`, `<BreadcrumbList>`, `<BreadcrumbItem>` component set?

---

### 5. Playwright / E2E test setup

**Status:** candidate

**Description:** Currently only Pest feature/unit tests exist. Need Playwright setup for component-level and flow-level E2E testing.

**App context:** User requested Playwright tests for all components.

**Reusable value:** High — every cloned app will need E2E testing infrastructure.

**Tasks:**
- Install Playwright
- Configure Playwright for Vue 3 + Inertia
- Create base test utilities
- Document how to write component tests and flow tests

**Questions:**
- Should Playwright tests be included in the template by default, or documented as optional?
- Should there be example Playwright tests for auth flows?

---

### 6. ui/Drawer component (refactor NavDrawer)

**Status:** candidate

**Description:** `NavDrawer.vue` exists as a top-level composition component (combines Dialog + sidebar layout + nav content). A `ui/Drawer.vue` would be a lower-level primitive (just the slide-in panel with open/close state). `NavDrawer` should be refactored to use `ui/Drawer`.

**App context:** Other parts of the app (or cloned apps) may need slide-in panels that are not sidebar navigation.

**Reusable value:** Medium — provides a reusable drawer primitive.

**Questions:**
- Should `ui/Drawer` support both left and right slide directions?
- Should it support different sizes (sm/md/lg/full)?
- Should `NavDrawer` be refactored to use it, or kept as-is?

---

## SSR

**Status:** already implemented

SSR setup exists in `resources/js/ssr.ts` and is documented in `profile.md`. No spec needed.

## Assumptions / Questions

- All items above are template-level reusable work, not app-specific.
- Priority should be determined by the user before creating detailed spec packets.
- Items 1 (Notification) and 5 (Playwright) have the highest reusable value.
