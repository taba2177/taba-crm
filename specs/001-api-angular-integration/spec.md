# Feature Specification: Laravel API Backend Hardening + Angular Dynamic Frontend Integration

**Feature Branch**: `001-api-angular-integration`
**Created**: 2026-03-12
**Status**: Draft
**Input**: User description: "Laravel API Backend Hardening + Angular Dynamic Frontend Integration"

## User Scenarios & Testing _(mandatory)_

### User Story 1 — API Serves Complete, Correctly Structured Data (Priority: P1)

A site visitor loads the Angular frontend. The frontend calls `/api/v1/init` and `/api/v1/home` during app initialization. Every piece of content the Angular `ContentService` needs — settings, posts, categories, menus, media URLs — must be present in the API response with the correct structure and absolute image URLs. Without this, every component falls back to static placeholder data.

**Why this priority**: All other dynamic features depend on the API returning well-structured data. This is the foundational prerequisite for every story below.

**Independent Test**: Send `GET /api/v1/settings/grouped` from a REST client. Verify the response is a flat `{ "key": "value" }` object containing all required settings keys (hero_title, site_name, contact_email, etc.) with non-empty Arabic defaults. Then verify `GET /api/v1/home` includes posts with `image_url` as an absolute HTTPS string, not just a numeric ID.

**Acceptance Scenarios**:

1. **Given** the CRM settings seeder has been re-run, **When** `GET /api/v1/settings/grouped` is called without authentication, **Then** the response body is a flat JSON object where every key in `[hero_title, hero_description, hero_cta_text, hero_cta_link, about_title, about_description, features_title, services_title, partners_title, branches_title, cta_title, cta_subtitle, cta_book_link, contact_email, contact_phone, contact_whatsapp, site_name, site_logo, footer_address, footer_links, social_facebook, social_twitter, social_instagram, social_linkedin]` is present with a non-null string value
2. **Given** at least one Post exists with an associated Media item, **When** `GET /api/v1/posts` is called, **Then** each post object in the response contains an `image_url` field that is an absolute URL (begins with `http://` or `https://`) resolving to the media file
3. **Given** at least one PostCategory exists with an associated Media item, **When** `GET /api/v1/categories` is called, **Then** each category object contains an `image_url` field that is an absolute URL
4. **Given** a Post with mixed content blocks (markdown, figure, heading, quote, list), **When** `GET /api/v1/posts/{slug}` is called, **Then** the response `content` array includes all blocks in order, each with `type` and `data` fields; figure blocks include a resolved absolute `image_url`
5. **Given** no authentication token is provided, **When** any of the public read endpoints (`/init`, `/home`, `/posts`, `/categories`, `/settings`, `/menus`, `/reviews`) are called, **Then** the response is 200 OK (not 401/403)

---

### User Story 2 — Angular Frontend Calls Laravel API Without CORS Errors (Priority: P1)

A developer runs the Angular app on `localhost:4200` and opens it in a browser. Every API call from `ApiService` reaches the Laravel backend without the browser blocking the request due to CORS policy. In production, the deployed Angular app domain is also allowed.

**Why this priority**: CORS misconfiguration makes the entire integration impossible at the browser level. It must be resolved before any other frontend work can be verified.

**Independent Test**: Start both apps (Angular dev server on port 4200, Laravel on a local server). Open the Angular app in a browser, check DevTools Network tab — no CORS preflight failures on any API call.

**Acceptance Scenarios**:

1. **Given** the Angular dev server is running on `http://localhost:4200`, **When** the browser sends a preflight `OPTIONS` request to any `/api/v1/` endpoint, **Then** the response includes `Access-Control-Allow-Origin: http://localhost:4200` and HTTP 200
2. **Given** the production Angular domain is configured in the backend CORS allowlist, **When** the deployed Angular app calls any `/api/v1/` endpoint, **Then** no CORS errors appear and the response includes the production origin in `Access-Control-Allow-Origin`
3. **Given** an arbitrary unlisted origin (e.g., `http://evil.com`), **When** it sends a CORS preflight, **Then** the response does NOT include that origin in `Access-Control-Allow-Origin`
4. **Given** the Angular app posts JSON to `/api/v1/contact`, **When** the preflight is sent with `Content-Type: application/json`, **Then** the CORS headers permit `Content-Type` in `Access-Control-Allow-Headers`

---

### User Story 3 — Visitor Submits Booking/Contact Form and Receives Feedback (Priority: P2)

A visitor fills in the multi-step booking/contact form in the Angular app and submits it. The system sends the data to the Laravel API, and the visitor sees a clear success confirmation or a meaningful error message — not a silent failure as currently occurs.

**Why this priority**: This is the primary conversion action on the site. The form currently collects data but throws it away — it is broken by definition.

**Independent Test**: Fill in all fields in the booking form and click Submit. Observe a success banner/message appears within the UI. Check the Laravel database for a new `contact_entries` record matching the submitted data.

**Acceptance Scenarios**:

1. **Given** a visitor completes all required fields in the booking form, **When** the final submission step is triggered, **Then** the form data is sent to `POST /api/v1/contact` and a success message is displayed to the visitor
2. **Given** a visitor submits the form and the API returns a validation error (422), **When** the response is received, **Then** the form displays the specific field-level error messages returned by the API (not a generic error)
3. **Given** a visitor submits the form and the network is unavailable, **When** the request times out, **Then** the form displays a network-error message and allows the visitor to retry without losing their input
4. **Given** a visitor submits the form successfully, **When** the success state is shown, **Then** the form fields are cleared and the visitor can initiate a new submission
5. **Given** the rate limit on `POST /api/v1/contact` has been reached (30 requests/minute from the same IP), **When** another submission is made, **Then** the API returns 429 and the Angular form shows a "please try again later" message

---

### User Story 4 — Dynamic Homepage Sections Replace Hardcoded Content (Priority: P2)

A content manager updates the "Line of Work" posts in the CMS, adds new feature bullets to the About section settings, and updates branch information. When the next visitor loads the homepage, all these components display the latest CMS data instead of the previously hardcoded values.

**Why this priority**: The components exist and render correctly — they just ignore the API data. Wiring them removes static content debt and enables non-developer content updates.

**Independent Test**: In the Laravel admin (Filament), update the title of a post in the "line-of-work" category. Reload the Angular homepage without deploying any code. The updated title appears in the `LineOfWorkComponent`.

**Acceptance Scenarios**:

1. **Given** posts in the `line-of-work` category exist in the API, **When** the Angular homepage loads and `ContentService` has resolved, **Then** `LineOfWorkComponent` displays the category posts (title, description, image) instead of the static `brands[]` array
2. **Given** the `line-of-work` category has no posts (or ContentService fails), **When** the homepage loads, **Then** `LineOfWorkComponent` falls back to the defined static data without crashing
3. **Given** `about_description` and feature bullet settings keys exist in the API settings, **When** the homepage loads, **Then** `AboutComponent` pills display the values from `ContentService` instead of the hardcoded `aboutPills[]` array
4. **Given** branch-related settings are populated in the CRM settings, **When** the `BranchesComponent` loads, **Then** it displays branch count and data from settings rather than hardcoded values (the SVG map itself remains unchanged)
5. **Given** partner posts exist in the API with `image_url` set, **When** the partners section loads, **Then** partner logos are displayed using the `image_url` from the API, not `assets/partners/*.png` paths

---

### User Story 5 — Route-Based Navigation to Blog, Posts, and Pages (Priority: P3)

A visitor clicks a blog post link and is navigated to a dedicated page showing the full content of that post. They can also browse posts by category and navigate to static CMS pages (e.g., Privacy Policy, About Us).

**Why this priority**: Current routing is entirely anchor-based (single-route SPA). Depends on Story 1 (post content serialization) being complete before full content rendering is verifiable.

**Independent Test**: Type `/blog/news/my-first-post` in the browser address bar directly. The Angular app renders the full post content without a blank screen or 404. Verify the post title, body blocks, and image are displayed.

**Acceptance Scenarios**:

1. **Given** the Angular router has a `/blog/:category/:slug` route, **When** a visitor navigates to that URL, **Then** `PostDetailComponent` renders the post's title, publication date, and all content blocks (markdown rendered as HTML, figures with resolved `image_url`, headings, quotes, lists)
2. **Given** the Angular router has a `/blog/:category` route, **When** a visitor navigates to that URL, **Then** `PostListComponent` renders a paginated list of posts in that category, each with title, thumbnail (`image_url`), and excerpt
3. **Given** the Angular router has a `/page/:slug` route, **When** a visitor navigates to it, **Then** `PageComponent` renders the CMS page content returned by the API
4. **Given** a visitor navigates to `/blog/:category` and there are no posts in that category, **When** the page loads, **Then** an empty-state message is shown (e.g., "No posts available") rather than a blank page
5. **Given** a visitor navigates to `/blog/:category/:slug` with a non-existent slug, **When** the API returns 404, **Then** the Angular app shows a user-friendly not-found message (not a blank screen or unhandled error)
6. **Given** the Angular app is server-side rendered (SSR), **When** a post detail page is requested from the server, **Then** the page HTML returned includes post title and visible content in the initial markup (enabling SEO crawlers)

---

### User Story 6 — Authentication Routes Are Accessible (Priority: P3)

A user navigates to `/login` or `/register`. The Angular app renders the corresponding auth form. After successful login, the user is redirected appropriately. `AuthService` already exists and is complete; only the routes and minimal form UI need wiring.

**Why this priority**: Auth flows are needed for future admin or user features. Depends on no other user story, so can be developed independently.

**Independent Test**: Navigate to `/login` in the browser. A login form is displayed. Submit valid credentials. The user is authenticated (token stored) and redirected away from `/login`.

**Acceptance Scenarios**:

1. **Given** the `/login` route exists, **When** a visitor navigates to it, **Then** a login form is displayed with email and password fields
2. **Given** valid credentials are submitted, **When** the login form is submitted, **Then** `AuthService` is called, the token is stored, and the user is redirected (e.g., to home or a protected page)
3. **Given** invalid credentials, **When** the login form is submitted, **Then** an error message is shown without redirecting
4. **Given** the `/register` route exists, **When** a visitor navigates to it, **Then** a registration form is displayed
5. **Given** an already-authenticated user navigates to `/login`, **When** the route activates, **Then** they are redirected away (guard prevents re-login)

---

### User Story 7 — Production Environment Is Deployable (Priority: P2)

A DevOps engineer runs the Angular production build. The `apiUrl` points to the real production domain, not a placeholder. The build completes without errors and the resulting artifact connects to the correct backend.

**Why this priority**: With a placeholder `apiUrl`, the production build is non-functional. This is a pre-deployment blocker.

**Independent Test**: Run `ng build --configuration production`. Inspect the output bundle — verify the hardcoded `https://your-domain.com` placeholder is replaced with the real API base URL.

**Acceptance Scenarios**:

1. **Given** the production `environment.ts` is updated with the correct API URL, **When** `ng build --configuration production` is run, **Then** the built artifact contains no `your-domain.com` placeholder string
2. **Given** the configuration guidance is documented, **When** a developer follows it, **Then** they can set the API URL for a new deployment without modifying source code outside of the designated environment files

---

### User Story 8 — API Rate Limiting Protects Public Endpoints (Priority: P3)

A public read endpoint is called at high frequency (e.g., by a scraper). After exceeding the configured threshold, the API responds with 429 Too Many Requests rather than degrading performance for all users.

**Why this priority**: Security and availability hardening. Does not block any user-visible feature but is a production requirement.

**Independent Test**: Use an HTTP benchmarking tool to send 150 requests/minute to `GET /api/v1/posts`. Verify that requests beyond the 100/min limit receive HTTP 429 responses with a `Retry-After` header.

**Acceptance Scenarios**:

1. **Given** the rate limiter is configured at 100 requests/minute for public read endpoints, **When** a client sends 101 requests within one minute, **Then** the 101st request receives HTTP 429 with a `Retry-After` header
2. **Given** the rate limiter is configured at 30 requests/minute for the contact/write endpoint, **When** 31 submissions are sent in one minute from the same IP, **Then** the 31st request receives HTTP 429
3. **Given** a client has been rate-limited, **When** the minute window expires, **Then** the client can make successful requests again

---

### User Story 9 — Dead Code and Legacy Database Objects Are Removed (Priority: P4)

A developer audits the Angular project and finds no unreferenced components. A backend developer audits the Laravel project and finds a migration documenting the deprecated `homepage_sections` table.

**Why this priority**: Maintenance and code clarity. No user-visible impact.

**Independent Test**: Search the `sbc-clean` workspace for any import of `Hero3Component` or the string `hero3`. None found. Run the Angular build — it completes without errors.

**Acceptance Scenarios**:

1. **Given** the `hero3/` directory is deleted from the Angular project, **When** the Angular app is built, **Then** no build errors appear and no references to `Hero3Component` exist in any file
2. **Given** the `homepage_sections` table deprecation migration is added, **When** a developer inspects the migrations folder, **Then** a migration file exists that drops the table, with a comment explaining it is superseded by `section_component` on `PostCategory`

---

### Edge Cases

- What happens when `ContentService` resolves before the settings seeder has been re-run (partially populated settings)? The `ContentService` must tolerate missing keys by falling back to static defaults without throwing runtime errors.
- What happens when a post's `image_url` is null because no media was attached? Angular components must treat `image_url` as optional (`string | null`) and render a fallback placeholder rather than breaking.
- How does the system handle an API timeout during `APP_INITIALIZER`? The existing 8-second timeout and fallback-to-static-data mechanism must remain intact; no regression is acceptable.
- What if a blog category slug in the URL does not match any category? `PostListComponent` shows an empty-state message; no unhandled exception is thrown.
- What if the Angular SSR server has no network access to the Laravel API? SSR must gracefully degrade to client-side rendering for API-dependent content rather than crashing the SSR process.
- What if `POST /api/v1/contact` receives a duplicate submission from the same visitor within 60 seconds? The rate limiter handles excessive submissions; individual duplicates are accepted (no additional deduplication required).

## Requirements _(mandatory)_

### Functional Requirements

#### Laravel Backend

- **FR-001**: The CRM settings seeder MUST populate all 24 settings keys (`hero_title`, `hero_description`, `hero_cta_text`, `hero_cta_link`, `about_title`, `about_description`, `features_title`, `services_title`, `partners_title`, `branches_title`, `cta_title`, `cta_subtitle`, `cta_book_link`, `contact_email`, `contact_phone`, `contact_whatsapp`, `site_name`, `site_logo`, `footer_address`, `footer_links`, `social_facebook`, `social_twitter`, `social_instagram`, `social_linkedin`) with default Arabic-language string values when the seeder is run on a fresh database
- **FR-002**: The `/api/v1/settings/grouped` endpoint MUST return a flat JSON object (`{ "key": "value" }`) — not nested groups or arrays — where every key from FR-001 is present
- **FR-003**: `PostResource` MUST include an `image_url` field containing a fully qualified absolute URL (scheme + host + path) for the associated media item, or `null` if no media is attached
- **FR-004**: `CategoryResource` (PostCategoryResource) MUST include an `image_url` field following the same resolution rule as FR-003
- **FR-005**: The `/api/v1/posts/{slug}` response MUST include a `content` array where each element has at minimum a `type` field (`markdown`, `figure`, `heading`, `quote`, `list`) and a `data` field; `figure`-type entries MUST include an `image_url` as an absolute URL
- **FR-006**: CORS MUST be configured to allow `http://localhost:4200` as an allowed origin for all `/api/v1/*` routes
- **FR-007**: CORS MUST be configurable (via environment or config file) to allow one or more additional production origins without code changes
- **FR-008**: CORS MUST permit the `Authorization`, `Content-Type`, and `Accept` headers in cross-origin requests
- **FR-009**: CORS MUST allow credentials to support Sanctum token-based auth flow
- **FR-010**: All public read endpoints (`/api/v1/init`, `/api/v1/home`, `/api/v1/posts`, `/api/v1/categories`, `/api/v1/settings`, `/api/v1/menus`, `/api/v1/reviews`) MUST apply a rate limit of 100 requests per minute per IP
- **FR-011**: The write endpoint `POST /api/v1/contact` MUST apply a rate limit of 30 requests per minute per IP
- **FR-012**: Rate-limited responses MUST return HTTP 429 with a `Retry-After` header indicating when the limit resets
- **FR-013**: A database migration file MUST exist that drops the `homepage_sections` table and includes a comment explaining it is superseded by the `section_component` column on `PostCategory`; this migration MUST be held without running automatically in production
- **FR-014**: Media items served by the API MUST resolve to absolute URLs using the application's configured storage base URL — no relative paths or numeric IDs are acceptable in API response fields where a URL is expected

#### Angular Frontend

- **FR-015**: `BookingComponent` MUST call the contact submission API endpoint on final form completion and MUST display a distinct success state upon receiving a successful response
- **FR-016**: `BookingComponent` MUST display field-level validation messages when the API returns a validation error response
- **FR-017**: `BookingComponent` MUST display a user-readable error message and allow retry without data loss when the API returns an error or times out
- **FR-018**: `LineOfWorkComponent` MUST read posts from `ContentService` using the `line-of-work` category; the static `brands[]` array MUST only be used as a fallback if no posts are available from the service
- **FR-019**: `AboutComponent` pills MUST be driven by settings values from `ContentService`; the hardcoded `aboutPills[]` array MUST only be used as a fallback when the service provides no data
- **FR-020**: `BranchesComponent` MUST read branch-count or branch-data settings from `ContentService`; the SVG map component MUST remain unchanged
- **FR-021**: Partner logo sources MUST use `image_url` from API post data, not local `assets/partners/*.png` paths
- **FR-022**: The `hero3/` directory and `Hero3Component` MUST be fully removed with no remaining imports or references anywhere in the Angular project
- **FR-023**: The production environment `apiUrl` value MUST NOT contain the placeholder string `your-domain.com`; the correct production API base URL MUST be set
- **FR-024**: Angular routes MUST exist for `/blog/:category`, `/blog/:category/:slug`, `/page/:slug`, `/login`, and `/register`
- **FR-025**: `PostListComponent` MUST call the posts API filtered by category slug and MUST render a paginated list of posts; each item MUST show at minimum: title, thumbnail (`image_url`), and excerpt
- **FR-026**: `PostDetailComponent` MUST call the single-post API by slug and render all content block types: markdown (as rendered HTML), heading, figure (with `image_url`), quote, and list
- **FR-027**: `PageComponent` MUST call the single-page API by slug and render the page content
- **FR-028**: All new route components (`PostListComponent`, `PostDetailComponent`, `PageComponent`) MUST use `OnPush` change detection strategy consistent with the existing project architecture
- **FR-029**: Auth routes (`/login`, `/register`) MUST connect to the existing `AuthService` methods; no new auth service or HTTP logic is to be created

### Key Entities

- **CrmSetting**: A key-value store entry. Attributes: `key` (string, unique), `value` (string), `group` (optional string). The settings API flattens all entries to a single `{ key: value }` object.
- **Post**: A content item. Attributes: `title`, `slug`, `excerpt`, `content` (array of typed blocks), `image_url` (resolved absolute URL or null), `category_id`, `published_at`. Belongs to one PostCategory. Each content block has `type` and `data`.
- **PostCategory**: A grouping for Posts. Attributes: `name`, `slug`, `section_component` (Blade component name), `image_url` (resolved absolute URL or null). Has many Posts. The `section_component` field supersedes the legacy `homepage_sections` table.
- **Page**: A static CMS page. Attributes: `title`, `slug`, `content`. Independent of PostCategory.
- **ContactEntry**: A contact/booking form submission. Attributes: `name`, `email`, `phone`, `message`, `service`, `created_at`. Created by `POST /api/v1/contact`.
- **Media**: A file attachment. Attributes: `id`, `path` (storage-relative), `disk`. The API MUST resolve this to a fully qualified URL before including it in any response.

---

## Success Criteria _(mandatory)_

### Measurable Outcomes

- **SC-001**: All 24 settings keys defined in FR-001 are present in the `/api/v1/settings/grouped` response with non-null values after running the seeder on a fresh database
- **SC-002**: Zero Angular `ContentService`-dependent components (hero, about, line-of-work, branches, partners) fall back to static placeholder data for any field when the API is reachable and the seeder has been run
- **SC-003**: Every booking form submission during testing results in a new record in the `contact_entries` database table — zero silent data-loss submissions
- **SC-004**: The Angular app produces zero CORS-related browser console errors during any API call when running against the Laravel backend from `localhost:4200`
- **SC-005**: All API post and category responses confirm `image_url` as a fully qualified URL string (verified for any record with an attached media item)
- **SC-006**: Navigating directly to `/blog/:category/:slug` renders visible post content (title and at least one content block) without a blank screen, error page, or unhandled console exception
- **SC-007**: The Angular production build completes without errors and the string `your-domain.com` does not appear in the compiled output
- **SC-008**: No file, import, directive, or reference to `Hero3Component` or the `hero3/` path remains in the Angular codebase after cleanup
- **SC-009**: A rate-limiting verification test (101 sequential requests/minute to any public read endpoint) produces at least one HTTP 429 response containing a `Retry-After` header
- **SC-010**: The `homepage_sections` deprecation migration file executes without error against a test database and successfully drops the table

---

## Assumptions

- The Angular project (`sbc-clean`) at `C:\WORK\sbc-clean` already has functioning `ApiService`, `ContentService`, and `AuthService` — no new service-layer classes are needed, only wiring existing components to these services.
- The `line-of-work` PostCategory uses the slug `line-of-work`; the exact slug should be confirmed during implementation and may be loaded from a settings key.
- "Arabic defaults" in the settings seeder means reasonable placeholder Arabic text (e.g., `"عنوان البطل"` for `hero_title`) that satisfies non-null constraints without requiring production content to be pre-filled.
- The Angular app uses `environment.ts` / `environment.prod.ts` for build-time configuration — no runtime configuration server is needed.
- SSR transfer-state optimization and SSR-specific API caching strategies are out of scope; the existing client-fallback behavior is acceptable.
- No new visual design is required for `PostListComponent`, `PostDetailComponent`, or `PageComponent`; they must match existing PrimeNG + Tailwind patterns already in use.
- The `homepage_sections` drop migration will be committed but NOT run automatically via deploy scripts; it requires a deliberate maintenance-window execution.
- Post content blocks are already stored in a structured format in the database; this feature addresses only API serialization, not content authoring UI.
- Auth form UI for `/login` and `/register` can be minimal (email/password fields with submit and error display) — no full design spec is required.
