# Angular SiteContent Interface Contract

**File**: `src/app/data/content.ts` (C:\WORK\sbc-clean)  
**Purpose**: Defines the canonical shape consumed by all Angular components. Maps from API data via `ContentService.mapToSiteContent()`. Falls back to `SITE_CONTENT` static defaults when API is unavailable.

---

## Full Interface (post-feature)

```typescript
// ─── Primitive interfaces ───────────────────────────────────────────

export interface HeroContent {
    title: string;
    description: string;
    ctaText: string;
    ctaLink: string;
    backgroundImage: string;
}

export interface Stat {
    value: number;
    label: string;
    icon: string;
    suffix?: string;
}

export interface AboutContent {
    sectionTitle: string;
    title: string;
    description: string;
    stats: Stat[];
    pills: string[]; // NEW — was hardcoded in AboutComponent
}

export interface Service {
    id: string;
    title: string;
    description: string;
    icon: string;
    image: string;
}

export interface ServicesContent {
    sectionTitle: string;
    title: string;
    description: string;
    services: Service[];
}

export interface Feature {
    title: string;
    icon: string;
}

export interface FeaturesContent {
    sectionTitle: string;
    title: string;
    description: string;
    features: Feature[];
}

export interface BranchesContent {
    sectionTitle: string;
    title: string;
    description: string;
    citiesCount: number;
}

export interface Partner {
    name: string;
    logo: string;
}

export interface PartnersContent {
    sectionTitle: string;
    title: string;
    description: string;
    partners: Partner[];
}

export interface CTAContent {
    title: string;
    subtitle: string;
    bookingText: string;
    bookingLink: string;
    calculatorText: string;
    calculatorLink: string;
    whatsappText: string;
    whatsappLink: string;
}

export interface FooterContent {
    logo: string;
    description: string;
    address: string;
    email: string;
    phone: string;
    whatsapp: string;
    socialLinks: { platform: string; url: string; icon: string }[];
    legalLinks: { label: string; url: string }[];
    copyright: string;
}

export interface NavItem {
    label: string;
    href: string;
}

// NEW — for LineOfWorkComponent
export interface LineOfWorkItem {
    title: string;
    subtitle: string;
    icon: string;
    imageUrl: string | null;
}

// ─── Top-level SiteContent ──────────────────────────────────────────

export interface SiteContent {
    siteName: string;
    logo: string;
    navigation: NavItem[];
    hero: HeroContent;
    about: AboutContent;
    services: ServicesContent;
    branches: BranchesContent;
    features: FeaturesContent;
    partners: PartnersContent;
    cta: CTAContent;
    footer: FooterContent;
    lineOfWork: LineOfWorkItem[]; // NEW
}
```

---

## SITE_CONTENT Additions (static defaults)

Only the new fields need to be added to the existing `SITE_CONTENT` constant. Existing fields are unchanged.

```typescript
// In the `about` section of SITE_CONTENT:
about: {
  // ... all existing fields unchanged ...
  pills: [
    'كوادر فنية مدربة',
    'معدات وتقنيات حديثة',
    'مواد آمنة ومعتمدة',
    'ضمان جودة الخدمة',
  ],
},

// New top-level field in SITE_CONTENT:
lineOfWork: [
  { title: 'تنظيف احترافي', subtitle: 'منازل وشركات ومؤسسات', icon: 'fa-solid fa-wand-magic-sparkles', imageUrl: null },
  { title: 'جلي ولمعة',     subtitle: 'رخام وسيراميك وبلاط',  icon: 'fa-regular fa-gem',              imageUrl: null },
  { title: 'تنظيف واجهات', subtitle: 'زجاج ومباني وأسطح',    icon: 'fa-regular fa-building',          imageUrl: null },
  { title: 'تعقيم خزانات', subtitle: 'مياه نظيفة وآمنة',     icon: 'fa-solid fa-droplet',             imageUrl: null },
],
```

---

## ContentService Mapping Contract

### `mapToSiteContent()` — New Sections

#### `mapAboutPills(settings, defaults)`

```typescript
// Read from settings key "about_pills" (JSON array of strings)
// Fallback: defaults.about.pills
private mapAboutPills(settings: ApiSettingsGrouped, defaults: SiteContent): string[] {
  const json = this.setting(settings, 'about_pills');
  if (json) {
    try {
      const parsed = typeof json === 'string' ? JSON.parse(json) : json;
      if (Array.isArray(parsed)) return parsed;
    } catch { /* ignore */ }
  }
  return defaults.about.pills;
}
```

#### `mapLineOfWork(home, defaults)`

```typescript
// Read from home.featured_posts filtered by category.slug === 'line-of-work'
// OR from home.categories with section_component === 'line-of-work' + their posts
// Maps ApiPost → LineOfWorkItem: { title, subtitle (excerpt), icon, imageUrl (image_url) }
// Fallback: defaults.lineOfWork
private mapLineOfWork(home: ApiHomeData | null, defaults: SiteContent): LineOfWorkItem[] {
  if (!home?.featured_posts?.length) return defaults.lineOfWork;
  const posts = home.featured_posts.filter(
    p => p.category?.slug === 'line-of-work'
  );
  if (!posts.length) return defaults.lineOfWork;
  return posts.map(p => ({
    title:    p.title,
    subtitle: p.excerpt ?? '',
    icon:     p.icon ?? 'fa-solid fa-check',
    imageUrl: p.image_url ?? null,
  }));
}
```

The updated `mapToSiteContent()` return object adds:

```typescript
return {
    // ... all existing mappings unchanged ...
    about: {
        // ... existing about mapping ...
        pills: this.mapAboutPills(settings, defaults), // NEW line added
    },
    lineOfWork: this.mapLineOfWork(home, defaults), // NEW field added
};
```

---

## Component Consumption Rules

| Component             | ContentService Field                              | Fallback                         |
| --------------------- | ------------------------------------------------- | -------------------------------- |
| `HeroComponent`       | `content().hero.*`                                | `SITE_CONTENT.hero.*`            |
| `AboutComponent`      | `content().about.*` + **`content().about.pills`** | `SITE_CONTENT.about.pills`       |
| `LineOfWorkComponent` | **`content().lineOfWork`**                        | `SITE_CONTENT.lineOfWork`        |
| `ServicesComponent`   | `content().services.*`                            | `SITE_CONTENT.services.*`        |
| `BranchesComponent`   | `content().branches.*`                            | `SITE_CONTENT.branches.*`        |
| `FeaturesComponent`   | `content().features.*`                            | `SITE_CONTENT.features.*`        |
| `PartnersComponent`   | `content().partners.*`                            | `SITE_CONTENT.partners.*`        |
| `BookingComponent`    | `content().services.services` (step 1)            | `SITE_CONTENT.services.services` |

Bold = new or changed in this feature.

---

## Invariants

1. `content()` signal is **never `null` or `undefined`** — it always holds at minimum `SITE_CONTENT`.
2. All new fields (`pills`, `lineOfWork`) have non-empty default values in `SITE_CONTENT` — no component ever renders an empty section due to missing API keys.
3. A missing `image_url` from the API (null) must be handled by components as optional. `LineOfWorkItem.imageUrl` is `string | null`.
4. `ContentService.setting()` tolerates missing/null values and returns `null` — callers use `?? defaults.field` pattern.
