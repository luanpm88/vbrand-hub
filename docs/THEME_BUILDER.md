# Theme Builder — Fullscreen Page Builder

## Tổng quan

Trang **Theme Builder** (`/brand/website/theme/options`) cho phép customer chỉnh sửa toàn bộ nội dung website WordPress thông qua giao diện fullscreen, tương tự Elementor / WordPress Customizer.

**URL:** `/brand/website/theme/options`

## Kiến trúc

```
┌──────────────────────────────────────────────────────────────────────┐
│  Toolbar: [← Quay lại]  Builder  [📱 💻 🖥] [↗] [●Đã lưu] [Reset] [Lưu] │
├─────────────────────────────────────┬────────────────────────────────┤
│                                     │  Sidebar (380px)               │
│   WordPress Preview (iframe)        │  ┌──────────────────────────┐  │
│   + PostMessage Bridge              │  │ CHUNG│MENU│HOME│ABOUT│..│   │
│                                     │  ├──────────────────────────┤  │
│   - Real-time update qua            │  │ Text inputs              │  │
│     postMessage (no reload)         │  │ Image uploads            │  │
│   - Scroll-to + highlight khi       │  │ Boolean toggles          │  │
│     focus input                     │  │ Select dropdowns         │  │
│   - Navigate khi click tab          │  │ List controls            │  │
│   - Responsive: mobile/tablet/      │  │ Rich text editors        │  │
│     desktop toggle                  │  └──────────────────────────┘  │
│                                     │                                │
└─────────────────────────────────────┴────────────────────────────────┘
```

## Data Flow

```
1. GET /brand/website/theme/options
   → WebsiteController::themeOptions()
   → $customer->wordpress()->themeGetOptions()    (cURL → vbrandsync API → WordPress DB)
   → $customer->wordpress()->themeGetMeta()       (cURL → vbrandsync API → schema.php)
   → render blade view with $themeOptions + $schema

2. Real-time Preview (PostMessage — NO server call)
   → User edits input in sidebar
   → JS reads data-selector + data-property from control
   → postMessage('vbrand-builder:update', {selector, property, value})
   → Bridge script in iframe updates DOM instantly

3. POST /brand/website/theme/options (AJAX save — only on "Lưu" click)
   → WebsiteController::themeOptions()
   → fillSchema() — recursive processor (text, image upload, list, boolean, select)
   → $customer->wordpress()->themeUpdateOptions() (cURL → vbrandsync API → WordPress DB)
   → JSON response { success: true, message: "..." }
   → Iframe reloads to reflect server-side changes

4. POST /brand/website/theme/options/reset (AJAX)
   → WebsiteController::themeOptionsReset()
   → $customer->wordpress()->themeOptionsReset()
   → JSON response → page reload
```

## PostMessage Bridge Protocol

Communication giữa builder (parent) và WordPress iframe (child) qua `window.postMessage`.

**Activation:** Iframe URL có `?vb_builder=1` → bridge script được inject qua `wp_footer`.

### Messages: Parent → Iframe

| Type | Payload | Mô tả |
|------|---------|-------|
| `vbrand-builder:update` | `{selector, property, value}` | Update DOM element |
| `vbrand-builder:scroll` | `{selector}` | Smooth scroll to element |
| `vbrand-builder:highlight` | `{selector}` | Highlight element (blue overlay, auto-fade 2.5s) |
| `vbrand-builder:navigate` | `{url}` | Navigate iframe to URL (auto-add `?vb_builder=1`) |
| `vbrand-builder:ping` | — | Health check |

### Messages: Iframe → Parent

| Type | Payload | Mô tả |
|------|---------|-------|
| `vbrand-builder:ready` | `{url, title}` | Bridge initialized (DOMContentLoaded) |
| `vbrand-builder:loaded` | `{url, scrollY}` | Page fully loaded (images, etc) |
| `vbrand-builder:pong` | `{url, title, scrollY}` | Response to ping |

### Supported Properties

| Property | Effect | Áp dụng cho |
|----------|--------|-------------|
| `textContent` | Set text | text fields |
| `innerHTML` | Set HTML | textarea/rich text |
| `src` | Set image/iframe src | image fields |
| `backgroundImage` | Set CSS background | image fields (hero, banner) |
| `display` | Show/hide (true/false) | boolean toggles |
| `href` | Set link URL | link fields |
| `color`, `fontSize`, etc | Set CSS property | select/text fields |

## Cross-Theme Convention (BẮT BUỘC)

Mọi theme phải tuân theo convention này để tương thích với builder.

### Schema Convention

```php
// schema.php
return [
    'sessions' => [
        [
            'name' => 'general',
            'title' => 'CHUNG',
            'page' => 'page-homepage.php',   // ← WP page template → iframe navigate
        ],
        [
            'name' => 'about-us',
            'title' => 'GIỚI THIỆU',
            'page' => 'page-aboutus.php',    // ← navigate to about page
        ],
    ],
    'options' => [
        [
            'session' => 'general',
            'type' => 'text',
            'name' => 'site_name',
            'label' => 'Tên Website',
            'default' => 'DreamCafe',
            'selector' => '.dc-header__logo-text, .dc-footer__logo span',  // ← CSS selector(s)
            'property' => 'textContent',     // ← DOM property to update
        ],
        [
            'session' => 'home',
            'type' => 'boolean',
            'name' => 'promo_show',
            'label' => 'Hiện banner quảng cáo',
            'default' => true,
            'selector' => '.dc-promo',
            'property' => 'display',         // ← show/hide
        ],
        [
            'type' => 'list',
            'name' => 'menus',
            'max' => 6,
            'schema' => [ /* nested options — each can also have selector/property */ ],
        ],
    ],
];
```

### Session `page` → Navigation

| Page template | Resolves to | Rule |
|---------------|-------------|------|
| `page-homepage.php` | `/` | Homepage |
| `page-aboutus.php` | `/about-us/` | Extract slug from template name |
| `page-contact.php` | `/contact/` | Extract slug from template name |

Convention: `page-{slug}.php` → `/{slug}/` (underscore → dash).

### CSS Selector Guidelines (cho theme developers)

1. **Prefix selectors** với theme code (vd: `.dc-` cho DreamCafe)
2. **Unique selectors** — mỗi option nên target 1 element hoặc group cùng nội dung
3. **Comma-separated** cho multiple targets (vd: header + footer cùng tên)
4. **Stable selectors** — không dùng nth-child hoặc dynamic classes
5. Selector đầu tiên (trước dấu phẩy) dùng cho scroll/highlight

### Bridge Script

**File:** `site/wp-content/plugins/vbrandsync/wordpress/builder-bridge.php`

- Inject via `wp_footer` hook (priority 9999) — only khi `?vb_builder=1`
- Outputs `<style>` for highlight overlay + `<script>` with message handler
- Auto-sends `ready` và `loaded` events to parent

## File Structure

```
app/
├── app/Http/Controllers/Brand/WebsiteController.php    → Controller (fillSchema, save, reset)
├── resources/views/brand/website/
│   ├── themeOptions.blade.php                          → Fullscreen builder (standalone HTML)
│   ├── themeOptions.blade.old.php                      → Backup of old layout
│   └── options/
│       ├── builderControl.blade.php                    → Dark-themed control partial (data-selector/data-property)
│       ├── renderControls.blade.php                    → Old control router (legacy)
│       └── _*.blade.php                                → Old controls (legacy)
site/
├── wp-content/plugins/vbrandsync/
│   ├── plugin.php                                      → includes builder-bridge.php
│   └── wordpress/builder-bridge.php                    → PostMessage bridge script
├── wp-content/themes/dreamcafe/
│   └── schema.php                                      → Schema with selector/property/page metadata
tests/
└── Browser/Webapp/ThemeBuilderTest.php                 → Dusk browser tests
```

## UI Design

### Standalone HTML
- **Không dùng** `@extends('layouts.core.frontend')` — trang hoàn toàn độc lập
- Bootstrap 5.3.3 CDN
- Google Fonts: Inter
- TinyMCE cho rich text editors (load từ `/core/tinymce/tinymce.min.js`)
- Dark theme sidebar (#23272f), light preview area

### Toolbar

| Element | Chức năng |
|---------|-----------|
| ← Quay lại | Link về trang danh sách templates |
| Theme Builder | Tiêu đề + indicator dot (xanh = online) |
| Mobile / Tablet / Desktop | Responsive preview toggle (430px / 768px / 100%) |
| Open in new tab | Mở site WordPress trong tab mới |
| Save indicator | Đã lưu / Chưa lưu / Đang lưu... (auto-detect) |
| Toggle sidebar | Ẩn/hiện sidebar |
| Reset | Xoá toàn bộ options về mặc định (có confirm) |
| Lưu | AJAX save + reload preview |

### Sidebar
- **Session tabs** — tab bar dạng ngang, scroll được, lấy từ `schema.sessions`
- **Tab click** → navigate iframe to session's `page` via PostMessage
- **Controls** — mỗi option trong schema render thành control tương ứng
- **Input change** → real-time preview update via PostMessage (no reload)
- **Input focus** → scroll + highlight element in preview
- **Scrollable** — sidebar content scroll độc lập
- **Collapsible** — toggle button bên cạnh hoặc toolbar button

### Control Types

| Type | UI Component | PostMessage |
|------|-------------|-------------|
| `text` | Dark input field | `textContent` on input |
| `textarea` | TinyMCE rich editor | `innerHTML` on change |
| `boolean` | Toggle switch | `display` show/hide |
| `select` | Dark dropdown | `textContent` on change |
| `image` | Thumbnail + upload | `src` with data URL preview |
| `list` | Accordion items | N/A (complex nested) |

### Responsive Preview

| Device | Width | Icon |
|--------|-------|------|
| Mobile | 430px | Phone |
| Tablet | 768px | Tablet |
| Desktop | 100% (default) | Monitor |

Non-desktop modes: iframe centered with border-radius + box-shadow, dark background.

## Keyboard Shortcuts

| Shortcut | Hành động |
|----------|-----------|
| `Ctrl+S` / `⌘+S` | Save (prevent browser default save dialog) |

## API Endpoints (Backend)

| Method | URL | Response |
|--------|-----|----------|
| GET | `/brand/website/theme/options` | HTML (builder page) |
| POST | `/brand/website/theme/options` | JSON `{success, message}` (AJAX) hoặc redirect (form) |
| POST | `/brand/website/theme/options/reset` | JSON `{success, message}` (AJAX) hoặc redirect |

## Browser Tests

**File:** `tests/Browser/Webapp/ThemeBuilderTest.php`

```bash
# Run all Theme Builder tests
php artisan dusk --filter ThemeBuilderTest

# Run with visible browser
DUSK_HEADLESS_DISABLED=true php artisan dusk --filter ThemeBuilderTest
```

### Test Coverage

| Test | Kiểm tra |
|------|----------|
| `test_builder_page_loads_without_errors` | Page load, no PHP errors, đúng layout |
| `test_builder_is_standalone_html` | Standalone HTML, Bootstrap 5 CDN, DOCTYPE |
| `test_toolbar_elements_present` | Back, Save, Reset buttons, indicator |
| `test_sidebar_tabs_present` | Schema session tabs rendered |
| `test_tab_switching_works` | Click tab → panel switch đúng |
| `test_sidebar_toggle` | Open/close sidebar toggle |
| `test_controls_rendered` | Text, textarea, toggle, select, image, list controls |
| `test_text_input_editable` | Edit text → unsaved indicator |
| `test_boolean_toggle_works` | Toggle switch flips state |
| `test_list_control_accordion` | Expand/collapse list items |
| `test_preview_iframe_loaded` | Iframe has valid src URL |
| `test_save_button_works` | AJAX save, no errors |
| `test_ctrl_s_shortcut` | Ctrl+S triggers save |
| `test_no_javascript_errors` | No SEVERE console errors |
| `test_full_edit_save_workflow` | Complete: load → edit → save → verify |

## Lưu ý khi phát triển

1. **Control mới**: Thêm vào `builderControl.blade.php` — nhớ thêm `data-selector` và `data-property`
2. **Schema thay đổi**: Chỉ cần update `schema.php` phía theme — builder tự render + real-time preview
3. **Styling**: CSS nằm inline trong `themeOptions.blade.php` `<style>` block
4. **JS logic**: Inline trong `<script>` block cuối file, vanilla JS (không jQuery)
5. **Preview**: Iframe src = `getPageUrl() + ?vb_builder=1` để activate bridge
6. **File upload**: FormData AJAX, image preview = data URL qua FileReader, cũng gửi PostMessage cho instant preview
7. **Theme mới**: Phải có `selector` + `property` trong schema options, `page` trong sessions
8. **Bridge script**: Tự động inject — theme developer không cần thêm code JS
