# Theme Builder — Fullscreen Page Builder

## Tổng quan

Trang **Theme Builder** (`/brand/website/theme/options`) cho phép customer chỉnh sửa toàn bộ nội dung website WordPress thông qua giao diện kéo thả fullscreen, tương tự Elementor / WordPress Customizer.

**URL:** `/brand/website/theme/options`

## Kiến trúc

```
┌─────────────────────────────────────────────────────────────┐
│  Toolbar: [← Quay lại]  Theme Builder  [●Đã lưu] [Reset] [Lưu] │
├────────────────────────────────────┬────────────────────────┤
│                                    │  Sidebar (380px)       │
│   WordPress Preview (iframe)       │  ┌──────────────────┐  │
│                                    │  │ CHUNG│MENU│HOME│..│  │
│   - Hiển thị site WordPress        │  ├──────────────────┤  │
│   - Tự reload sau khi save         │  │ Text inputs      │  │
│   - Full height                    │  │ Image uploads    │  │
│                                    │  │ Boolean toggles  │  │
│                                    │  │ Select dropdowns │  │
│                                    │  │ List controls    │  │
│                                    │  │ Rich text editors│  │
│                                    │  └──────────────────┘  │
└────────────────────────────────────┴────────────────────────┘
```

## Data Flow

```
1. GET /brand/website/theme/options
   → WebsiteController::themeOptions()
   → $customer->wordpress()->themeGetOptions()    (cURL → vbrandsync API → WordPress DB)
   → $customer->wordpress()->themeGetMeta()       (cURL → vbrandsync API → schema.php)
   → render blade view with $themeOptions + $schema

2. POST /brand/website/theme/options (AJAX save)
   → WebsiteController::themeOptions()
   → fillSchema() — recursive processor (text, image upload, list, boolean, select)
   → $customer->wordpress()->themeUpdateOptions() (cURL → vbrandsync API → WordPress DB)
   → JSON response { success: true, message: "..." }
   → JS reloads iframe preview

3. POST /brand/website/theme/options/reset (AJAX)
   → WebsiteController::themeOptionsReset()
   → $customer->wordpress()->themeOptionsReset()
   → JSON response → page reload
```

## File Structure

```
app/
├── app/Http/Controllers/Brand/WebsiteController.php    → Controller (fillSchema, save, reset)
├── resources/views/brand/website/
│   ├── themeOptions.blade.php                          → Fullscreen builder (standalone HTML)
│   ├── themeOptions.blade.old.php                      → Backup of old layout
│   └── options/
│       ├── builderControl.blade.php                    → NEW: dark-themed control partial
│       ├── renderControls.blade.php                    → Old control router (legacy)
│       ├── _text.blade.php                             → Old text control (legacy)
│       ├── _textarea.blade.php                         → Old textarea (legacy)
│       ├── _image.blade.php                            → Old image (legacy)
│       ├── _boolean.blade.php                          → Old boolean (legacy)
│       ├── _select.blade.php                           → Old select (legacy)
│       └── _list.blade.php                             → Old list (legacy)
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
| Save indicator | Đã lưu / Chưa lưu / Đang lưu... (auto-detect) |
| Toggle sidebar | Ẩn/hiện sidebar |
| Reset | Xoá toàn bộ options về mặc định (có confirm) |
| Lưu | AJAX save + reload preview |

### Sidebar
- **Session tabs** — tab bar dạng ngang, scroll được, lấy từ `schema.sessions`
- **Controls** — mỗi option trong schema render thành control tương ứng
- **Scrollable** — sidebar content scroll độc lập
- **Collapsible** — toggle button bên cạnh hoặc toolbar button

### Control Types

| Type | UI Component | Ghi chú |
|------|-------------|---------|
| `text` | Dark input field | `.ctrl-input` |
| `textarea` | TinyMCE rich editor | `.builder-rich-editor`, dark skin |
| `boolean` | Toggle switch | iOS-style, hidden input + checkbox |
| `select` | Dark dropdown | Custom arrow, dark options |
| `image` | Thumbnail + upload button | 60x60 preview, file reader for instant preview |
| `list` | Accordion items | Collapsible headers, add/remove, nested controls |

## Keyboard Shortcuts

| Shortcut | Hành động |
|----------|-----------|
| `Ctrl+S` / `⌘+S` | Save (prevent browser default save dialog) |

## Schema-Driven

Toàn bộ UI được generate từ `schema.php` của theme (ví dụ DreamCafe). Schema gồm:

```php
return [
    'sessions' => [
        ['name' => 'general', 'title' => 'CHUNG'],
        ['name' => 'menu', 'title' => 'MENU'],
        // ...
    ],
    'options' => [
        [
            'session' => 'general',
            'type' => 'text',          // text | textarea | boolean | select | image | list
            'name' => 'site_name',     // form field name
            'label' => 'Tên Website',  // display label
            'default' => 'DreamCafe',  // fallback value
        ],
        [
            'type' => 'list',
            'name' => 'menus',
            'max' => 6,
            'schema' => [ /* nested options */ ],
        ],
        // ...
    ],
];
```

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

# Run specific test
php artisan dusk --filter test_full_edit_save_workflow
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

1. **Control mới**: Thêm vào `builderControl.blade.php` (không phải file `_*.blade.php` cũ)
2. **Schema thay đổi**: Chỉ cần update `schema.php` phía theme — builder tự render
3. **Styling**: CSS nằm inline trong `themeOptions.blade.php` `<style>` block
4. **JS logic**: Inline trong `<script>` block cuối file, vanilla JS (không jQuery)
5. **Preview**: Iframe src lấy từ `Auth::user()->customer->wordpress()->getPageUrl()`
6. **File upload**: FormData AJAX với `enctype="multipart/form-data"`, image control giữ `_original` hidden input
