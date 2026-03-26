# AcelleMail KB — UI Principles

## Frontend (Public)

### Design System
- **Layout**: `resources/views/layouts/kb.blade.php` — all CSS inline in `<style>` block
- **Fonts**: Fraunces (headings, serif), IBM Plex Sans (body, sans), IBM Plex Mono (code)
- **Colors**: `#E8571A` (orange primary), `#241C15` (dark text), `#FEF7F2` (cream bg)
- **Code highlighting**: Prism.js CDN (Tomorrow theme)
- **No CSS framework** — custom design system with `.kb-*` class prefix

### Key Components
- `.kb-container` — max-width 1200px centered
- `.kb-article-layout` — 2-column grid (content + sticky TOC sidebar)
- `.kb-card` — article cards with hover shadow
- `.kb-prose` — article body typography (headings, code, tables, blockquotes)
- `.kb-filter-bar` — horizontal filter dropdowns with auto-submit

## Backend (Admin)

### Framework: Bootstrap 5.3 CDN
- CSS: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css`
- JS: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js`
- Icons: `https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css`

### CSS Override: `public/css/admin.css`

**Rules:**
1. Override Bootstrap via CSS variables — never hardcode colors/spacing
2. Use `--bs-primary: #E8571A` for brand consistency
3. Use Bootstrap helpers first (mt-3, text-muted, d-flex, etc.)
4. Only add custom CSS in `admin.css` when Bootstrap doesn't cover it
5. Prefix custom classes with `kb-admin-*`

```css
/* Good — use Bootstrap variable */
:root { --bs-primary: #E8571A; }

/* Bad — hardcoded color */
.my-button { background: #E8571A; }
```

### Dark/Light Mode

Built on Bootstrap 5.3's `data-bs-theme` attribute:

```html
<html data-bs-theme="light"> <!-- or "dark" -->
```

- Toggle button in admin topbar
- Theme persisted in `localStorage('kb-admin-theme')`
- `admin.css` uses `[data-bs-theme="dark"]` selectors for custom overrides
- All Bootstrap components auto-adapt to theme

### Layout Structure

```
┌──────────────────────────────────────────────┐
│ Sidebar (240px fixed)  │  Topbar (sticky)    │
│                        │  ┌──────────────┐   │
│ Brand logo             │  │ Breadcrumb   │   │
│ ─────────              │  │    Theme  User│   │
│ Content                │  └──────────────┘   │
│  ● Articles            │                     │
│  ● Categories          │  Content area       │
│  ● Tags               │  (padded 1.5rem)    │
│ Links                  │                     │
│  ● View Site           │                     │
│  ● AcelleMail          │                     │
└──────────────────────────────────────────────┘
```

### Editor: EasyMDE

**CDN:**
```html
<link href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
```

**Two editing modes:**
1. **Markdown** (default): EasyMDE visual markdown editor with toolbar
2. **HTML Source**: Toggle to raw textarea for direct HTML/markdown editing

**Configuration:**
```javascript
new EasyMDE({
    element: document.getElementById('body_markdown'),
    spellChecker: false,
    autosave: { enabled: true, uniqueId: 'kb-article-' + articleId },
    toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'code',
              'unordered-list', 'ordered-list', '|', 'link', 'image',
              'table', 'horizontal-rule', '|', 'preview', 'side-by-side',
              'fullscreen', '|', 'guide'],
});
```

### Component Patterns

**Tables:** Always use `table table-hover` with `table-striped` for list views
**Forms:** Use `card` wrapper, `form-label` + `form-control`, `row` + `col-*` grid
**Badges:** Status colors via custom classes: `.badge-published`, `.badge-draft`, `.badge-archived`
**Buttons:** `btn-primary` for create/save, `btn-outline-secondary` for cancel, `btn-danger` for delete
**Alerts:** `alert-success` for success messages, `alert-danger` for errors (auto-dismissible)
