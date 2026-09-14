# CurrentSign Mobile Application
## Scope of Work (SOW) — Flutter (iOS & Android)

| Field | Value |
|---|---|
| Product | CurrentSign (Current Sign) |
| Document type | Client-facing Scope of Work |
| Version | **2.0** |
| Date | 14 September 2026 |
| Supersedes | SOW v1.0 |
| Prepared for | CurrentSign product owner |
| Prepared by | Product + Solution Architecture + Flutter Engineering |
| Platforms | iOS 14+ and Android 8.0 (API 26)+ |
| Client commitment | Remaining APIs, payments, transactions, and social login backends **will be developed** as part of this programme |
| Design | Live web UI is the visual contract until Figma is supplied (**TBC**) |

### Status legend (used throughout)

| Status | Meaning |
|---|---|
| **Existing** | Already live in the Laravel web app and/or `/api` |
| **Existing — polish** | Exists but is incomplete, inconsistent, or unsafe; backend will fix as part of this programme |
| **Will develop** | Not in the current product. Backend and/or mobile will be built under this SOW |
| **TBC** | Product or vendor choice still open (does not block writing the contract, may block a milestone) |

This document is production-oriented. It does **not** pretend Stripe or social login already exist on the website. It records what is true today, then specifies what **will be developed**.

---

## 1. Executive summary

CurrentSign is an e-signature workspace: upload PDFs, email them for signature, sign in a browser editor, track **Awaiting** vs **Signed**, store files and notes.

The Flutter app is a **premium, mobile-first** client of the same product. Brand (navy + gold), business rules (trial of 5 sends, then paid plans), and document flows stay aligned with the web app.

**v2 decisions locked by the client**

1. Every API the app needs that is missing or broken **will be developed** on Laravel (Passport JSON APIs). Flutter will not fake data or call leftover hotel/social endpoints.
2. **Mobile commerce is In-App Subscriptions**, to satisfy Apple Guideline 3.1.1 and Google Play payments policy for digital services.
3. **Wallet UX:** Apple Pay (iOS) and Google Pay (Android) will be available to the subscriber. How that is wired is specified in §13 — it is **not** “Stripe Payment Sheet instead of StoreKit”.
4. **Stripe** will be introduced as the **web + billing-system of record** (today the repo has **PayPal**, not Stripe). Stripe Apple Pay / Google Pay wallets will be configured for **web checkout** and for unified customer/subscription records. Mobile store purchases are verified with Apple/Google, then stored alongside Stripe-originated web subscriptions.
5. **Payments, invoices, and transaction history** will be developed end-to-end (backend + app).
6. **Social login** (Apple and Google; Facebook **TBC**) **will be developed**. If Google (or any third-party login) ships on iOS, **Sign in with Apple is mandatory** (Guideline 4.8).

Phase 1 signing remains the existing web editor in a **WebView** (`/signature?id=`, `/edit-pdf/{id}`). A native PDF SDK is a later phase unless contracted separately.

---

## 2. What exists today (web) vs what will be developed

### 2.1 Current web product (source of UX and rules)

| Area | Today |
|---|---|
| Marketing | Home, About, Pricing (Basic $15 / Standard $49 / Premium $99 hardcoded), Contact, legal pages |
| Auth | Email + password; forgot password via OTP (OTP hardcoded `9999` on web and API) |
| Workspace | Dashboard, Documents, Notes, Profile, Notifications (sidebar) |
| E-sign | Send PDF + email + page; recipient opens public signing page; PDF.js editor; upload signed PDF; email owner |
| Trial | `users.is_trial`; after 5 `signatures` rows, send is blocked on API (`403`) |
| Admin | Separate `/admin` — **out of mobile scope** |
| Payments on web | **PayPal** REST (`PayPalController`, `paypal/rest-api-sdk-php`). Pricing CTAs currently go to **login**, not a completed checkout |
| Stripe | **Not present** in `composer.json`, `.env.example`, or app code |
| Social login | **Not present**. Admin login has a commented Facebook button only |
| Push / FCM | Not present |
| Analytics / crash SDKs | Not present |

### 2.2 Contacts / folders / projects

Present in older web routes, **not** in the current customer sidebar. **Out of Phase 1** unless the client adds them as a change request. Folder copy/move API methods are missing.

### 2.3 Web vs mobile auth

| Channel | Mechanism |
|---|---|
| Website | Laravel `web` session |
| Mobile | Laravel Passport `api` guard — `Authorization: Bearer {access_token}` |

JSON envelope (**Existing**):

```json
{
  "success": true,
  "data": {},
  "message": "",
  "notification": [],
  "error": [],
  "status": "1"
}
```

Many endpoints return **HTTP 200** even on failure. The app must trust `success`, not status codes alone. Backend **Will develop** consistent HTTP codes (401/403/422) as polish; the app will support both.

---

## 3. Conversion strategy (web → mobile)

| Web | Mobile |
|---|---|
| Marketing nav | Guest: Welcome, Login/Register/Social, Pricing |
| Sidebar | Bottom nav: Dashboard, Documents, Notes, Profile |
| Send-document modal | Full screen: PDF + email + page |
| PDF.js editor | In-app WebView (Phase 1) |
| Pricing → Login | Pricing → **Subscribe** (StoreKit / Play Billing) + restore + transaction history |
| Email/password only | Email/password **plus social login (Will develop)** |

```
Flutter (GetX)
  REST  →  Laravel /api          (auth, workspace, billing, social — mix of Existing + Will develop)
  WebView →  {WEB}/signature     (Existing editor)
  StoreKit / Play Billing → Apple / Google  →  Laravel verify + entitlements (Will develop)
  Web billing → Stripe (Will develop) + optional remaining PayPal until cutover
```

---

## 4. Premium mobile UI / UX theme

From `cs-home.css`. Centralised in Flutter `AppTheme` / `AppColors`.

| Token | Hex | Use |
|---|---|---|
| Navy | `#0C1525` | Auth/marketing chrome, logo field |
| Navy mid / light | `#152035` / `#1E3148` | Dark surfaces |
| Gold | `#C5A27D` | Primary CTA, active tab, badges |
| Gold light / dark | `#D4B896` / `#A6845E` | Highlight / pressed |
| Accent | `#B45309` | Badge emphasis |
| Canvas | `#F8F5F1` | Logged-in background |
| Surface / border | `#FFFFFF` / `#E8E2DA` | Cards |
| Text / muted | `#1E293B` / `#64748B` | Hierarchy |
| Success | `#065F46` | Signed, paid, restored |
| Danger | alert red | Delete, payment failed |

Typography: **Inter** (UI), **DM Serif Display** (Welcome / plan names). Font license **TBC**. Logo: existing `CS-2-TBG.png`; store 1024 icon **TBC**.

Components: 10–16 dp radii, gold filled primary buttons, ghost gold/navy secondary, no emoji decoration. Empty-state copy matches the website where it exists.

Paywall UI: current plan chip, three plan cards aligned to website copy, Apple/Google purchase sheet (system UI), “Restore purchases”, legal links, auto-renewal disclaimer required by Apple.

Social buttons: official Apple / Google marks (brand guidelines). Apple button must be visible on iOS if any other social provider is shown.

---

## 5. User roles

| Role | Mobile | Notes |
|---|---|---|
| Guest | Yes | Auth, pricing (view), legal, **public Sign WebView** |
| Registered user | Yes | Workspace + subscriptions |
| Recipient signer | Yes | No login wall on `/signature?id=` (Existing web rule) |
| Paid subscriber | Yes **Will develop** | Entitlement from IAP or Stripe web |
| Trial user | Yes **Existing** | `is_trial` until 5 sends or until a paid plan starts — **Will develop** exact interaction |
| Admin | No | Out of scope |

Team seats advertised on Premium/Standard **marketing** are **not** implemented. Not in Phase 1.

---

## 6. Navigation

**Guest:** Splash → Welcome → Login / Register / Social / Forgot / OTP / Create password / Pricing / Legal / Sign WebView (deep link).

**Authenticated bottom nav:** Dashboard · Documents · Notes · Profile.

**Stacks:** Send for signature, Document viewer, Note add/edit, Sign WebView, Edit PDF WebView, Plans & subscribe, Transaction history, Restore, Social account linking **TBC**.

---

## 7. Authentication

### 7.1 Email / password (**Existing** + polish)

| Method | Path | Status | App use |
|---|---|---|---|
| POST | `/api/auth/signup` | Existing — polish | Register. Today requires `dob` + regex password; website does not. **Will develop:** align fields with website **or** document API rules and show them in the app. |
| POST | `/api/auth/login` | Existing | `identity` + `password` → `token`, `user_data`. Phase 1 app uses **email** as identity (website parity). |
| POST | `/api/auth/get-profile` | Existing | Session restore; `AwaitingCount`, `signedCount`. **Will develop:** include `plan`, `subscription_status`, `is_trial`, entitlements. |
| POST | `/api/auth/update-profile` | Existing | `full_name`, `mobile_number`, `address` only from the app. |
| POST | `/api/auth/change-password` | Existing | `old_password`, `password`, `c_password`. |
| POST | `/api/auth/password-reset` | Existing — polish | OTP currently `9999`. **Will develop:** real email OTP, consistent columns. |
| POST | `/api/auth/verify-otp` | Existing — polish | |
| POST | `/api/auth/create-new-password-without-login` | Existing — polish | Today reads `otp_reset_password` (not the field `password-reset` writes). **Will develop:** one OTP pipeline. |
| POST | `/api/auth/create-new-password` | Existing — **must not ship as-is** | Takes raw `user_id`. **Will develop:** remove or bind to verified OTP/token. |
| POST | `/api/auth/delete-account` | Existing | Store requirement. **Will develop:** also revoke IAP? cannot cancel store sub from server without user — show instructions. |
| POST | `/api/auth/logout` | **Will develop** | Revoke Passport token. App also wipes secure storage. |

Token: `flutter_secure_storage`. Treat `success == false` + message `Unauthorized.` as 401.

### 7.2 Social login (**Will develop**)

Not on the website today. In scope because the client requires it.

| Provider | iOS | Android | Notes |
|---|---|---|---|
| Sign in with Apple | Required if any social is on iOS | Optional | Hide-my-email; identity token + nonce |
| Google Sign-In | Yes if social ships | Yes | Google ID token |
| Facebook | **TBC** | **TBC** | Not on current web; only add if client confirms |

**Backend (Will develop)**

`POST /api/auth/social-login`

Proposed contract (backend owns final schema):

- `provider`: `apple` \| `google` \| `facebook` (if enabled)
- `id_token` (Apple/Google) or Facebook access token
- `nonce` (Apple)
- `full_name` (may be empty on later Apple logins)
- `email` (may be `privaterelay.appleid.com`)

Behaviour:

- If provider user is new → create `users` row, return Passport token.
- If email matches existing password account → **link** provider (**Will develop** linking rules; avoid account takeover).
- If provider already linked → login.

`POST /api/auth/social-link` and `POST /api/auth/social-unlink` — **Will develop** if Profile shows connected accounts.

**Flutter:** `sign_in_with_apple`, `google_sign_in`; Facebook plugin only if TBC = yes. Never send provider secrets from the app.

**Guideline 4.8:** Apple button equal prominence on iOS.

### 7.3 Auth flows

1. Splash → token? `get-profile` → shell or Welcome.  
2. Register (email) or social → MainShell.  
3. Login email/social → MainShell.  
4. Forgot → OTP email (**Will develop** real mail) → new password → Login.  
5. Logout → **Will develop** revoke + local wipe.  
6. Delete account → confirm → API → wipe.

**States:** button loading, field errors from `message`, social cancel (no error toast), Apple relay email explained once.

**Edge cases:** Apple second login without name; Google account with no email; social user has no password (hide “old password” or require “set password” — **Will develop**).

---

## 8. Module specifications

---

### 8.1 Splash & Welcome

**Purpose.** Brand and routing.  
**Screens.** Splash; Welcome.  
**Features.** Logo, website headline, Log In, Start Free, social entry points after M1.  
**API.** `get-profile` if token present (**Existing**).  
**States.** Branded loader; offline with token → **Will develop** policy (default: attempt profile, else Welcome).  
**Dependencies.** Logo, `API_BASE_URL`.

---

### 8.2 Login / Register

**Purpose.** Create or restore a user.  
**Screens.** Login; Register.  
**Features.** Email, password, confirm, confidentiality checkbox (UI parity). Social buttons.  
**API.** login, signup **Existing — polish**; social-login **Will develop**.  
**Edge cases.** Signup `dob` until backend drops it. Email-only identity on the form.

---

### 8.3 Forgot password

**Purpose.** Recover access.  
**API.** reset / verify / set password **Existing — polish** + **Will develop** single OTP model + SMTP.  
**Until polish lands:** UI exists but UAT cannot pass. Milestone acceptance waits on backend.

---

### 8.4 Dashboard

**Purpose.** Counts, awaiting, signed, send CTA.  
**Features.** Match `dashboard.blade.php`: Total Docs, Notes, Awaiting, Signed; Upload; Send for Signature; list rows with View / Open Sign Link / Edit PDF.

**API**

| Need | Status |
|---|---|
| Personal counts | `get-profile` counts **Existing**; file/note counts on `get-dashboard` are **global** today → **Will develop** user-scoped `POST /api/dashboard` |
| Signed list | `POST /api/folders/getSignedSignatures` **Existing — polish** (status case + `public/` URL) |
| Awaiting list | **Will develop** `POST /api/signatures/awaiting` (same shape as signed) |

**States.** Skeletons; website empty copy; pull-to-refresh; retry.  
**Edge cases.** Null email; mixed status casing; missing PDF path.  
**Dependencies.** Dashboard + awaiting APIs **Will develop**.

---

### 8.5 Send for signature

**Purpose.** Create envelope, email recipient.  
**Features.** PDF picker, email (required in app), page default 1.  
**Business logic.** Trial 5 sends then `is_trial=false`. App honours **403** and opens paywall.  
**API.** `POST /api/folders/submitSignature` **Existing — polish** (always return JSON even without email; fix nested return).  
**Will develop:** server-side plan limits (10 / 50 / unlimited) once subscriptions exist — not only the 5-doc trial.  
**States.** Upload progress; 10 MB client cap unless backend documents otherwise **TBC**; success toast.  
**Dependencies.** Mail from `sign@currentsign.com` **Existing**.

---

### 8.6 Sign & Edit PDF (WebView)

**Purpose.** Existing PDF.js editor.  
**URLs Existing:** `{WEB}/signature?id={id}`, `{WEB}/edit-pdf/{id}`. Completion: web `POST /upload-file`.  
**Will develop:** JSON `POST /api/signatures/{id}/complete` as an alternative so the WebView is not the only path — **optional**; Phase 1 remains WebView.  
**Will develop:** Android/iOS file chooser in WebView (app work). Universal Links **Will develop**.  
**Edge cases.** Guest signer; PDF 404 if `public/` prefix remains — **polish**.  
**Phase 2 (not default):** native PDF SDK — separate quote.

---

### 8.7 Documents

**Purpose.** File locker.  
**Features.** List, upload (`file` + optional display name), view, delete confirm. Website also writes a notification row.

| Action | Status |
|---|---|
| List user files | Controller exists, **not routed** → **Will develop** `POST /api/documents/list` |
| Upload (no folder) | Controller exists, **not routed** → **Will develop** `POST /api/documents/upload` (do **not** hardcode `folder_id = 2`) |
| Delete | `POST /api/folders/file/delete` **Existing** — confirm same table → **polish** if needed |

**States.** Empty: “No documents uploaded yet.”  
**Dependencies.** Routed APIs **Will develop**.

---

### 8.8 Notes

**Purpose.** Title + description CRUD.  
**API Existing:** `notes`, `note`, `create-note`, `update-note`, `delete-note`. List is **not user-scoped** → **Will develop** filter by token user.  
**States.** Website empty/add/edit patterns.

---

### 8.9 Profile & security

**Purpose.** Name, read-only email, phone, address, password, logout, delete.  
**API Existing** as §7.1. **Will develop:** `plan` badge, “Manage subscription”, connected social accounts.  
**Image on profile:** API supports it, website does not — **TBC** (default omit).

---

### 8.10 Notifications

**Purpose.** `notification_text` / `notification_message` (website, last 5).  
**Existing wrong API:** `/api/posts/getNofifications` (other product) — **do not use**.  
**Will develop:** `POST /api/notifications` (paginated), optional `POST /api/notifications/read`.  
**Push (FCM/APNs):** **Will develop** if client confirms in the same programme (recommended with signing events). APIs: `POST /api/device/register` (`fcm_token`, `platform`, `device_id`).  
**Until push APIs exist:** in-app list only.

**Default in this SOW:** in-app list **in scope (Will develop)**; push **in scope (Will develop)** unless client later descope.

---

### 8.11 CMS / contact / legal

| Item | Status |
|---|---|
| Privacy, terms, about, FAQ, support GET | **Existing** `/api/common/*` |
| Logged-in support message | **Existing** `POST /api/common/ask_support` (`message` only) |
| Guest contact form | Web `POST /contact-submit` (session) → **Will develop** `POST /api/contact` (`name`, `email`, `message`) |
| Plans content | Blade hardcoded **Existing**; `GET /api/common/plans` table **Existing** but unused by the page → **Will develop** canonical `GET /api/billing/plans` used by web **and** app |

---

## 9. Billing, subscriptions, wallets, transactions

This section is the commercial core of v2.

### 9.1 Policy (why the app cannot “just use Stripe Apple Pay” for unlock)

CurrentSign sells a **digital subscription** (document quota / product features).  

- **Apple 3.1.1:** digital subscriptions purchased **in the iOS app** must use **In-App Purchase (StoreKit)**. Apple Pay that appears during IAP is Apple’s own sheet.  
- **Google Play:** in-app digital subscriptions must use **Play Billing**. Google Pay appears inside that sheet.  
- **Stripe Payment Sheet + Apple Pay / Google Pay inside the Flutter app to unlock Basic/Standard/Premium will be rejected** for this product type.

**Correct split (this SOW):**

| Channel | Commerce system | Wallet the user sees |
|---|---|---|
| **iOS app** | StoreKit 2 In-App Subscriptions **Will develop** | Apple Pay / cards on file — provided by **Apple**, not Stripe |
| **Android app** | Google Play Billing subscriptions **Will develop** | Google Pay — provided by **Play** |
| **Website** | **Stripe Billing Will develop** (replace or run beside today’s **PayPal Existing**) | Stripe-hosted **Apple Pay and Google Pay wallets** enabled in Stripe Dashboard / Payment Element |
| **Entitlements** | Laravel `subscriptions` + `transactions` **Will develop** | One user can be `active` from IAP **or** Stripe web; app reads the same profile flags |

This is how “Apple policy in-app subscriptions” and “Apple Pay / Google Pay via Stripe configuration” coexist without store rejection.

### 9.2 Plans (product)

Match website pricing until `GET /api/billing/plans` is live:

| Plan | Price (web) | Docs / month (marketing) | Store product IDs **Will develop** |
|---|---|---|---|
| Trial (free) | $0 | 5 lifetime sends (**Existing** code) | none |
| Basic | $15 / month | 10 / month | e.g. `currentsign_basic_monthly` |
| Standard | $49 / month | 50 / month | e.g. `currentsign_standard_monthly` |
| Premium | $99 / month | Unlimited (marketing) | e.g. `currentsign_premium_monthly` |

Annual SKUs, family sharing, intro offers: **TBC**. Default Phase 1: **monthly auto-renewable** only.

**Will develop** server-side enforcement: trial 5; Basic 10/month; Standard 50/month; Premium unlimited — not marketing-only.

### 9.3 Mobile purchase flow

1. User opens Plans (from Profile, 403 trial, or dashboard banner).  
2. App loads `GET /api/billing/plans` (**Will develop**) + StoreKit/Play product metadata (real store price/currency — **must be displayed**; do not show only hardcoded $15 if the store localises).  
3. User taps Subscribe → native purchase sheet (Apple Pay available on iOS if the user has it).  
4. On store success, app sends receipt / purchase token to backend.  
5. Backend verifies with Apple/Google, writes `transactions` + `subscriptions`, updates `users`.  
6. App refreshes profile; paywall dismisses.  
7. Restore purchases on same or new device.

### 9.4 Backend APIs (**all Will develop** unless noted)

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/billing/plans` | Canonical plans, features, store product IDs, Stripe price IDs for web |
| GET | `/api/billing/entitlement` | Current plan, status (`trialing`/`active`/`grace`/`expired`/`cancelled`), period end, cancel_at_period_end, source (`iap_apple`/`iap_google`/`stripe`/`paypal_legacy`) |
| POST | `/api/billing/iap/verify` | Body: `platform`, `product_id`, `verification_data` (iOS JWS / Android purchase token), `transaction_id` |
| POST | `/api/billing/iap/restore` | Re-validate known store transactions for this user |
| POST | `/api/billing/webhooks/apple` | App Store Server Notifications V2 |
| POST | `/api/billing/webhooks/google` | Play Real-Time Developer Notifications |
| POST | `/api/billing/webhooks/stripe` | Stripe `invoice.paid`, `customer.subscription.*`, `charge.refunded` |
| GET | `/api/billing/transactions` | Paginated history for the app |
| GET | `/api/billing/transactions/{id}` | Receipt summary, amount, currency, status, date, plan |
| POST | `/api/billing/stripe/checkout` | **Web only** — create Stripe Checkout/Subscription session (Apple Pay/Google Pay enabled in Stripe) |
| POST | `/api/billing/stripe/portal` | Stripe Customer Portal (web manage/cancel) |
| GET | `/api/paypal/*` | **Existing** legacy web PayPal — keep until Stripe cutover **TBC** |

Refunds, chargebacks, grace periods, billing retry: handled on webhooks **Will develop**; app only displays status.

### 9.5 Flutter billing implementation

- Official `in_app_purchase` (StoreKit 2 + Google Play Billing Library).  
- Listen `purchaseStream`; complete / acknowledge purchases only **after** `iap/verify` succeeds.  
- Pending, cancelled, already-owned, billing-unavailable states.  
- **Restore** button (Apple requirement).  
- Do **not** ship `flutter_stripe` Payment Sheet as the iOS/Android **subscription** checkout. Stripe SDK may be used only if a future **non-digital** product is added (**not in this SOW**).  
- StoreKit Configuration file + Play Console products are **client + backend + Flutter** joint setup.

### 9.6 Transaction history (app)

**Screen.** Transactions.  
**Features.** Date, plan name, amount + currency, status (paid, refunded, failed, pending), source (App Store, Play, Stripe). Tap → detail. Empty: “No transactions yet.”  
**API.** `GET /api/billing/transactions` **Will develop**.  
**Edge cases.** Price differs by country (show store currency); missing web Stripe invoices until webhook backfill.

### 9.7 Cancel / change plan

Store subscriptions: user cancels in **Apple Settings / Play subscriptions**. App shows a deep link to manage + reads webhook status.  
Web Stripe: Customer Portal.  
**Will develop** UI copy explaining this (Apple rejects apps that pretend the developer can instantly cancel IAP without the store).

Upgrade/downgrade: **Will develop** store `changePlan` / replacement mode **TBC** (immediate vs period end).

### 9.8 Client Stripe configuration (web)

**Will develop** on Stripe Dashboard (client):

- Products/prices matching the three plans  
- Apple Pay domain verification for the **website**  
- Google Pay in Payment Element / Checkout  
- Customer portal  
- Webhook signing secret to Laravel  
- Tax **TBC** (Stripe Tax)

This does **not** replace IAP on mobile.

---

## 10. API catalogue

### 10.1 Existing (mobile will consume)

`POST /api/auth/signup` · `login` · `get-profile` · `update-profile` · `change-password` · `password-reset` · `verify-otp` · `create-new-password-without-login` · `delete-account`  
`POST /api/folders/submitSignature` · `getSignedSignatures` · `file/delete` · `file/upload` (folder-based)  
`POST /api/notes` · `note` · `create-note` · `update-note` · `delete-note`  
`GET /api/common/plans` · `get_privacy_policy` · `get_terms_and_condition` · `get_about_us` · `get_faqs` · `get_support`  
`POST /api/common/ask_support`  
PayPal GETs — **web legacy only**  
Web: `/signature`, `/edit-pdf/{id}`, `POST /upload-file`

### 10.2 Existing — polish (**Will develop** fixes)

- User-scoped notes list and dashboard counts  
- PDF URLs without `/public/`  
- `submitSignature` always returns JSON; status case `signed`/`Signed`  
- OTP columns + real email  
- Disable unsafe `create-new-password` by `user_id`  
- HTTP 401/403/422 consistency  
- Signup field alignment with website  
- `get-profile` includes billing entitlement  

### 10.3 Will develop (authoritative list)

**Auth:** `logout`; `social-login`; `social-link` / `social-unlink` (if needed); `set-password` for social-only users.

**Workspace:** `POST /api/dashboard`; `POST /api/signatures/awaiting`; `GET/POST` signature detail JSON (optional); `POST /api/signatures/{id}/resend`; `POST /api/signatures/{id}/complete` (optional native/WebView hybrid); `POST /api/documents/list`; `POST /api/documents/upload`; `POST /api/notifications`; `POST /api/notifications/read`; `POST /api/device/register`; `POST /api/contact`.

**Billing:** all endpoints in §9.4.

**Do not develop / do not call:** `/api/posts/*`, `/api/follows/*`, `/api/chats/*`, `/api/booking/*`, `/api/reviews/*`, car/hotel controllers, `/api/home/get-home`.

Exact paths may be namespaced (`/api/v1/...`) **TBC**; Flutter will use a single `Endpoints` class.

---

## 11. Push notifications (**Will develop**)

| Event | Audience |
|---|---|
| Document sent | Sender confirmation optional **TBC** |
| Please sign | Recipient if they have the app + token **TBC** (email remains source of truth) |
| Document signed | Sender |
| Subscription renewed / failed / expired | Payer |

APIs: device register; payload includes `type`, `signature_id`. Tap opens Dashboard or Sign WebView.

---

## 12. Analytics & crash monitoring (**Will develop** — recommended)

Not in the web app today. **In scope unless descoped:**

- Firebase Crashlytics **or** Sentry (**TBC** vendor)  
- Screen views: Login, Dashboard, Send, Sign, Plans, Purchase success/fail (no PDF contents)  
- Purchase funnels (store errors)

---

## 13. Security

- TLS; no cleartext in release  
- Token in secure storage; not logged  
- WebView allow-list of CurrentSign hosts; JS on for PDF.js  
- Receipts sent only over TLS to Laravel; never trust client `is_premium` boolean alone  
- Social tokens verified **server-side**  
- Stripe webhook signature verification **Will develop**  
- Apple/Google notification authenticity **Will develop**  
- Account deletion **Existing**  
- Certificate pinning **TBC**  
- Screenshot block on sign screen **TBC** (default off)

---

## 14. Performance

- `ListView.builder`; pagination **Will develop** when lists grow  
- Parallel Dashboard fetches  
- Dispose WebView on pop  
- GetX `Bindings`; permanent: `AuthService`, `ApiClient`, `BillingService`  
- No offline document vault in Phase 1

---

## 15. QA

- Unit: envelope parser, entitlement mapping, URL sanitiser  
- Widget: login, paywall, empty dashboard  
- Manual: full e-sign on iOS + Android WebView file picker  
- **Sandbox IAP** (StoreKit + Play license testers)  
- Restore, already-owned, cancelled sheet, interrupted purchase  
- Social: first login, second login, Apple hide-my-email  
- Trial 403 → purchase → send succeeds  
- Regression vs leftover API misuse (none called)

---

## 16. Platforms & environments

| | |
|---|---|
| iOS | 14+ |
| Android | 8 / API 26+ |
| Deep links | **Will develop** `https://{host}/signature?id=` |
| Flavours | `dev` / `staging` / `prod` with `API_BASE_URL` and `WEB_BASE_URL` |

Stores: bundle IDs **TBC**; TestFlight + Play internal; privacy URLs from website; IAP products created before UAT.

---

## 17. App Store / Play listing extras (billing)

- Privacy nutrition: purchase history, identifiers, email  
- Subscription terms, auto-renew legal text, EULA or standard Apple EULA **TBC**  
- Restore purchases visible  
- Sign in with Apple if Google is on iOS  
- Account deletion  
- Do not mention “pay with Stripe inside the app” in review notes; describe StoreKit / Play Billing and web Stripe separately

---

## 18. Deliverables

1. Flutter source (GetX feature architecture, §26).  
2. README + flavours.  
3. `.aab` + TestFlight build.  
4. Billing sandbox test notes.  
5. UAT checklist against screen inventory.  
6. Known issues if any webhook is still staging-only.  
7. Hypercare length **TBC** (recommend 2 weeks post-release).

Backend deliverables (client / backend team, same programme): §10.2, §10.3, Stripe account, IAP products, webhooks.

---

## 19. Client responsibilities

- Staging/prod URLs, test users, sample PDFs  
- Apple Developer + Play Console + IAP product creation  
- Stripe account, Apple Pay domain for **web**, Google Pay on Stripe  
- Google Cloud OAuth + Apple Services ID for social login  
- Backend development of all **Will develop** APIs before related Flutter UAT  
- Figma if it should override web UI  
- Legal copy for subscriptions  
- Decision on Facebook login and annual SKUs  

---

## 20. Assumptions

- Phase 1 signing = WebView of current editor  
- Backend will develop missing APIs rather than Flutter inventing parallel contracts  
- Mobile paid upgrade = IAP; web paid upgrade = Stripe (PayPal only until cutover)  
- English-only unless TBC  
- No admin app  
- One entitlement system on Laravel for web + mobile  

---

## 21. Out of scope (unless change request)

- Native PDF editor SDK  
- Admin mobile  
- Team seats, bulk send, templates (marketing-only today)  
- Contacts CRM, folders UI, projects/items  
- Chat, posts, hotels, cars leftover APIs  
- Using Stripe Payment Sheet as the **in-app** subscription checkout  
- Cryptocurrency, wallet-connect, etc.

---

## 22. Milestones

Backend **Will develop** work runs in parallel; Flutter UAT of a milestone requires the matching APIs.

| ID | Flutter | Backend must be ready |
|---|---|---|
| M0 | Architecture, theme, flavours, shell | URLs, test account |
| M1 | Email auth + **social login UI** | Social-login API, OTP email polish, logout |
| M2 | Dashboard, documents, notes, profile | Dashboard, awaiting, documents routes, notes scope, notifications list |
| M3 | Send + Sign/Edit WebView + deep links | Signature polish, PDF URLs, optional complete/resend |
| M4 | **Plans, IAP, restore, transactions, entitlement gates** | Billing APIs, Apple/Google webhooks, Stripe web + wallets on web, plan limits |
| M5 | Push, analytics/crash, store submission | Device register, FCM keys, store listing |

Forgot-password and IAP **cannot** be accepted on dummy OTP / dummy verify.

---

## 23. Screen inventory (Phase 1)

| # | Screen | Status of data |
|---|---|---|
| 1 | Splash | Existing profile ping |
| 2 | Welcome | Static |
| 3 | Login | Existing + social Will develop |
| 4 | Register | Existing — polish |
| 5 | Forgot / OTP / New password | Existing — polish / Will develop mail |
| 6 | Social loading / error | Will develop |
| 7 | Dashboard | Will develop awaiting + scoped counts |
| 8 | Send for signature | Existing — polish |
| 9 | Sign WebView | Existing web |
| 10 | Edit PDF WebView | Existing web |
| 11–12 | Documents list / upload | Will develop routes |
| 13–15 | Notes list / add / edit | Existing — polish scope |
| 16 | Profile | Existing + entitlement Will develop |
| 17 | Change password | Existing |
| 18 | Notifications | Will develop |
| 19 | Plans & subscribe | Will develop |
| 20 | Purchase pending / success / fail | Will develop |
| 21 | Restore purchases | Will develop |
| 22 | Transaction list / detail | Will develop |
| 23 | Manage subscription (deep link copy) | Will develop |
| 24 | Contact | Will develop public API + Existing ask_support |
| 25 | Privacy / Terms / Legal | Existing GET + WebView fallback |
| 26 | Delete account | Existing |

---

## 24. Loading / empty / error / success (global)

Every fetching screen: skeleton or button spinner; website empty copy; `message` banner + Retry; 401 → logout; success snackbar or navigation. Paywall: store error codes mapped to plain language (“Purchase cancelled”, “Already subscribed”, “Store unavailable”).

---

## 25. Technical stack (Flutter)

| Layer | Choice |
|---|---|
| Flutter / Dart 3 | Stable, pinned |
| State, DI, routes | **GetX** |
| Architecture | Feature-first: `core`, `data`, `modules/<feature>` |
| Network | Single `ApiClient` + interceptors |
| IAP | `in_app_purchase` |
| Social | `sign_in_with_apple`, `google_sign_in` (+ Facebook **TBC**) |
| WebView | `webview_flutter` + file chooser |
| Storage | `flutter_secure_storage` |
| Files | `file_picker`, `url_launcher` |
| Push | `firebase_messaging` **Will develop** |
| Crash/analytics | TBC vendor |
| Stripe in the **app** | **Not used for subscriptions** |
| Lint / tests | `flutter_lints`, `flutter_test` |

```
lib/app/modules/
  splash/ auth/ dashboard/ documents/ signatures/
  notes/ profile/ notifications/ billing/ cms/ shell/
lib/app/core/network|theme|storage|widgets
lib/app/data/models|repositories
```

Controllers never call Dio directly. `BillingService` owns the purchase stream.

---

## 26. TBC (narrow remaining choices)

1. Figma vs web UI  
2. Facebook login yes/no  
3. Annual SKUs / free trial in **store** vs current 5-doc trial  
4. Plan change replacement mode  
5. Stripe Tax  
6. PayPal sunset date vs Stripe-only web  
7. Crash vendor (Crashlytics vs Sentry)  
8. Bundle IDs, store screenshots owner, hypercare length  
9. Profile photo  
10. Native PDF Phase 2  
11. Pagination page size  

---

## 27. Acceptance (v2)

Accepted when a tester can:

- Register with email **and** with Apple (iOS) and Google  
- Use dashboard lists (awaiting + signed) from **new** APIs  
- Send a PDF, sign in WebView, see it Signed  
- Hit trial limit, purchase a **sandbox** subscription via StoreKit/Play (Apple Pay path on a capable iOS device), gain entitlement, send again  
- See the transaction in-app  
- Restore purchases  
- Open legal pages and delete the account  

Web Stripe Apple Pay/Google Pay is accepted on **staging website**, not as the Flutter paywall.

This SOW is the implementation contract. Backend **Will develop** items are in the same programme, not hidden Flutter workarounds.
