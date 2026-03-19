# vBrand Theme System - Complete Design Documentation

## Mục Lục
1. [Tổng Quan Theme System](#1-tổng-quan-theme-system)
2. [Kiến Trúc Theme](#2-kiến-trúc-theme)
3. [Schema System (`schema.php`)](#3-schema-system-schemaphp)
4. [ThemeData Class — Engine Đọc/Ghi](#4-themedata-class--engine-đọcghi)
5. [Data Flow: Từ Schema đến Render](#5-data-flow-từ-schema-đến-render)
6. [API Theme — Đọc/Ghi Từ Laravel App](#6-api-theme--đọcghi-từ-laravel-app)
7. [Page Templates — Cách Render](#7-page-templates--cách-render)
8. [Tham Chiếu Schema Hiện Tại (Logitech)](#8-tham-chiếu-schema-hiện-tại-logitech)
9. [Hướng Dẫn Developer](#9-hướng-dẫn-developer)
10. [Tạo Theme Mới Từ Đầu](#10-tạo-theme-mới-từ-đầu)

---

## 1. Tổng Quan Theme System

vBrand sử dụng hệ thống **theme-driven schema** cho phép brand owner customize toàn bộ giao diện website mà **không cần biết WordPress**. Mọi thao tác được thực hiện qua Laravel dashboard hoặc mobile app.

### Concept
```
schema.php (định nghĩa cấu trúc)
    → ThemeData (engine đọc/ghi)
    → WordPress options table (lưu trữ)
    → Page templates (render)
    → Laravel App UI / Mobile App (brand owner edit)
```

### Nguyên tắc
- **Schema là contract**: Mỗi theme PHẢI có `schema.php` định nghĩa toàn bộ options
- **Default data**: Schema chứa giá trị mặc định — theme hoạt động ngay khi activate
- **Dynamic rendering**: Page templates đọc data từ ThemeData, không hardcode
- **Edit từ xa**: Brand owner edit options qua Laravel dashboard → sync về WP qua REST API

---

## 2. Kiến Trúc Theme

### File Structure
```
site/wp-content/themes/{theme-name}/
├── schema.php              ← QUAN TRỌNG: Định nghĩa toàn bộ schema
├── functions.php           ← Theme setup, WooCommerce support, menu, custom statuses
├── style.css               ← Theme metadata (WordPress standard)
├── screenshot.png          ← Theme thumbnail
│
├── header.php              ← Header default (inner pages)
├── header-home.php         ← Header cho homepage (có slider-ready layout)
├── header-shop.php         ← Header cho shop pages
├── footer.php              ← Footer default
├── footer-home.php         ← Footer homepage (social icons, widgets từ schema)
├── footer-shop.php         ← Footer shop
│
├── page-homepage.php       ← Template: Trang chủ
├── page-aboutus.php        ← Template: Giới thiệu
├── page-shop.php           ← Template: Gian hàng
├── page-news.php           ← Template: Tin tức
├── page-contact.php        ← Template: Liên hệ
├── page-tintuc.php         ← Template: Bài viết chi tiết
├── page-dichvu.php         ← Template: Dịch vụ
├── page-hotro.php          ← Template: Hỗ trợ
├── page-post.php           ← Template: Chi tiết bài viết
├── page-compare.php        ← Template: So sánh sản phẩm
├── page-cart.php           ← Template: Giỏ hàng
├── page-checkout.php       ← Template: Thanh toán
│
├── woocommerce/            ← Override WooCommerce templates
│   ├── content-product.php
│   ├── myaccount/
│   ├── order/
│   └── emails/
│
├── css/
│   ├── skin.css            ← Theme-specific skin
│   └── customs.css         ← Custom overrides
│
└── assets/
    ├── css/                ← Bootstrap, plugins
    ├── js/                 ← jQuery, owl-carousel, etc.
    └── images/             ← Default images, icons
```

### Quan hệ giữa Theme và Plugin
```
┌─────────────────────────────────────────────────┐
│  WordPress Site                                  │
│                                                  │
│  ┌─────────────────────┐  ┌──────────────────┐  │
│  │  Plugin: vbrandsync  │  │ Theme: logitech  │  │
│  │                      │  │                  │  │
│  │  wordpress/theme.php │──│  schema.php      │  │
│  │  ├ vbrand_load_      │  │  page-*.php      │  │
│  │  │  theme_data()     │  │  header-*.php    │  │
│  │  │                   │  │  footer-*.php    │  │
│  │  app/Library/        │  │                  │  │
│  │  ├ ThemeData.php     │  │  $themeData->    │  │
│  │  │ .get()            │◄─│   get('slider')  │  │
│  │  │ .set()            │  │   get('menus')   │  │
│  │  │ .getThemeMeta()   │  │   get('news')    │  │
│  │  │ .updateTheme      │  │                  │  │
│  │  │  Options()        │  │                  │  │
│  │  │ .resetTheme       │  │                  │  │
│  │  │  Options()        │  │                  │  │
│  │                      │  │                  │  │
│  │  wordpress/api/      │  │                  │  │
│  │  ├ theme.php         │  │                  │  │
│  │  │ REST endpoints    │  │                  │  │
│  └──────────────────────┘  └──────────────────┘  │
└─────────────────────────────────────────────────┘
```

---

## 3. Schema System (`schema.php`)

### 3.1 Cấu Trúc File

`schema.php` return 1 array PHP với 2 key chính:

```php
return [
    'sessions' => [...],  // Nhóm/tab để organize trong UI editor
    'options'  => [...],  // Danh sách các field có thể customize
];
```

### 3.2 Sessions (Nhóm)

Sessions dùng để nhóm các options theo tab khi hiển thị trong editor UI:

```php
'sessions' => [
    ['name' => 'general',  'title' => 'CHUNG'],
    ['name' => 'menu',     'title' => 'MENU'],
    ['name' => 'home',     'title' => 'HOME'],
    ['name' => 'about-us', 'title' => 'ABOUT US'],
    ['name' => 'partner',  'title' => 'Partner'],
],
```

Mỗi option có `session` field trỏ về `name` của session.

### 3.3 Option Types

Có **5 loại field** trong schema:

#### 1. `text` — Input text đơn giản
```php
[
    'session' => 'general',
    'type'    => 'text',
    'name'    => 'site_name',
    'label'   => 'Tên website',
    'default' => 'vBrand Theme One',
]
```

#### 2. `textarea` — Nhiều dòng text
```php
[
    'session' => 'home',
    'type'    => 'textarea',
    'name'    => 'shop_banner_one_alias',
    'label'   => 'Shop Banner Alias',
    'default' => '',
]
```

#### 3. `boolean` — Toggle on/off
```php
[
    'session' => 'home',
    'type'    => 'boolean',
    'name'    => 'products_module_show',
    'label'   => 'Show Products Module',
    'default' => true,
]
```

#### 4. `image` — Upload hình ảnh
```php
[
    'session' => 'home',
    'type'    => 'image',
    'name'    => 'shop_banner_one',
    'label'   => 'Shop Banner 1',
    'default' => get_template_directory_uri() . '/assets/images/banners/shop-1.jpg',
]
```

Khi edit qua Laravel dashboard:
- Upload file mới → `$customer->uploadWebsiteFile($file)` → trả về URL
- Giữ ảnh cũ → gửi `{name}_original` field

#### 5. `select` — Dropdown lựa chọn
```php
[
    'type'    => 'select',
    'name'    => 'type',
    'label'   => 'Type',
    'default' => 'all',
    'options' => [
        ['value' => 'all',      'text' => 'Mặc định'],
        ['value' => 'hot',      'text' => 'Hot'],
        ['value' => 'featured', 'text' => 'Featured'],
        ['value' => 'new',      'text' => 'New'],
    ],
]
```

### 3.4 List Type — Nhóm lặp lại (phức hợp)

Đây là loại quan trọng nhất — cho phép tạo **danh sách các items**, mỗi item có cấu trúc riêng:

```php
[
    'session' => 'home',
    'type'    => 'list',          // ← Loại list
    'name'    => 'slider',        // ← Key lưu trữ
    'label'   => 'Slider',
    'max'     => 10,              // ← Tối đa bao nhiêu items

    // Schema cho MỖI item trong list
    'schema' => [
        [
            'type'    => 'image',
            'name'    => 'anh',
            'label'   => 'Tải ảnh',
            'default' => '',
        ],
        [
            'type'    => 'text',
            'name'    => 'slidertitle',
            'label'   => 'Tiêu đề slider',
            'default' => '',
        ],
        // ... thêm field khác
    ],

    // Dữ liệu mặc định (mẫu)
    'default' => [
        [
            'anh'          => get_template_directory_uri().'/assets/images/mx-banner.jpg',
            'slidertitle'  => 'Họp mặt',
            'slidertitlesub' => 'Truyền phát',
            // ...
        ],
    ],
]
```

**Cách đọc trong template:**
```php
<?php foreach($themeData->get('slider') as $slider): ?>
    <img src="<?php echo $slider['anh']; ?>">
    <h2><?php echo $slider['slidertitle']; ?></h2>
<?php endforeach; ?>
```

---

## 4. ThemeData Class — Engine Đọc/Ghi

**File:** `site/wp-content/plugins/vbrandsync/app/Library/ThemeData.php`

### 4.1 Storage

Theme options được lưu trong **Laravel settings table** (không phải WP options table):
- Key: `theme.options`
- Value: JSON string chứa tất cả theme options, tổ chức theo theme name

```json
{
    "Logitech Theme": {
        "site_name": "My Brand Store",
        "menus": [...],
        "slider": [...],
        "footer_widget_1": [...]
    },
    "Another Theme": {
        "site_name": "Other Store"
    }
}
```

### 4.2 Methods

| Method | Mô tả |
|--------|--------|
| `get($name, $default)` | Lấy giá trị option. Fallback: saved value → $default → schema default |
| `set($name, $value)` | Set 1 option |
| `getThemeOptions()` | Lấy tất cả options của theme hiện tại |
| `getAllThemeOptions()` | Lấy tất cả options của tất cả theme |
| `getThemeMeta()` | Đọc schema.php → trả về array |
| `getMetaOption($name)` | Tìm 1 option trong schema theo name |
| `updateThemeOptions($options)` | Merge update nhiều options |
| `resetThemeOptions()` | Xóa toàn bộ options, quay về default |

### 4.3 Fallback Chain

Khi `$themeData->get('slider')` được gọi:

```
1. Kiểm tra options đã save (từ DB) → có → return
2. Kiểm tra $default parameter → có → return
3. Kiểm tra schema.php → tìm option có name='slider' → return default
4. Return null
```

Đây là lý do theme **luôn có dữ liệu** khi mới activate — schema.php cung cấp default.

### 4.4 Khởi tạo

```php
// Trong theme template:
$themeData = vbrand_load_theme_data();

// Hàm này (trong plugin vbrandsync/wordpress/theme.php):
function vbrand_load_theme_data() {
    if (!class_exists('\App\Models\Setting')) {
        vbrandsync_getResponse('/');  // Autoload Laravel
    }
    return new \App\Library\ThemeData();
}
```

**Quan trọng:** `vbrand_load_theme_data()` phải autoload Laravel trước vì `ThemeData` dùng `App\Models\Setting` (Laravel model).

---

## 5. Data Flow: Từ Schema đến Render

### 5.1 Khi Theme Activate Lần Đầu

```
1. WordPress activate theme "logitech"
2. functions.php chạy vbrand_shop_activate()
3. → Đọc menus từ schema (via themeData->get('menus'))
4. → Tạo WordPress pages cho mỗi menu item (page-homepage.php, page-aboutus.php, etc.)
5. → Set frontpage = page-homepage.php
6. Mọi page template render data từ schema defaults (chưa có saved data)
```

### 5.2 Khi Brand Owner Edit Theme Options

```
Brand Owner (Laravel Dashboard / Mobile App)
    │
    │  POST /brand/website/theme-options
    │  (hoặc qua REST API)
    │
    ▼
Laravel WebsiteController::themeOptions()
    │
    │  1. Lấy schema: $customer->wordpress()->themeGetMeta()
    │     → GET /wp-json/vbrandsync/v1/theme/meta
    │
    │  2. Lấy current options: $customer->wordpress()->themeGetOptions()
    │     → GET /wp-json/vbrandsync/v1/theme/options/get
    │
    │  3. fillSchema() — Merge request data vào options:
    │     - text/textarea/boolean/select: copy value
    │     - image: upload file → get URL → save URL
    │     - list: recursive fillSchema cho từng item
    │
    │  4. Save: $customer->wordpress()->themeUpdateOptions($options)
    │     → POST /wp-json/vbrandsync/v1/theme/options/update
    │
    ▼
WordPress ThemeData::updateThemeOptions($options)
    │
    │  Merge vào Setting::get('theme.options') JSON
    │
    ▼
Trang web render ngay lập tức với data mới
```

### 5.3 Khi Visitor Truy Cập Website

```
Visitor → WordPress → page-homepage.php
    │
    │  $themeData = vbrand_load_theme_data();
    │  $themeData->get('slider')  → Lấy slider data
    │  $themeData->get('menus')   → Lấy menu items
    │  $themeData->get('news')    → Lấy news items
    │
    │  foreach(...) → Render HTML với data
    │
    ▼
HTML Response → Browser
```

---

## 6. API Theme — Đọc/Ghi Từ Laravel App

### REST Endpoints (vbrandsync plugin)

| Endpoint | Method | Mô tả |
|----------|--------|--------|
| `/wp-json/vbrandsync/v1/theme/list` | GET | Danh sách themes + active status |
| `/wp-json/vbrandsync/v1/theme/activate` | POST | Kích hoạt theme (POST `theme={name}`) |
| `/wp-json/vbrandsync/v1/theme/meta` | GET | Lấy schema.php → trả về sessions + options |
| `/wp-json/vbrandsync/v1/theme/options/get` | GET | Lấy saved options của theme hiện tại |
| `/wp-json/vbrandsync/v1/theme/options/update` | POST | Update options (POST `options[key]=value`) |
| `/wp-json/vbrandsync/v1/theme/options/reset` | POST | Reset về default |
| `/wp-json/vbrandsync/v1/page/url` | GET | Lấy home URL |

### Laravel Wordpress Model Methods

```php
// Trong Acelle\Wordpress\Wordpress class:
$wp = $customer->wordpress();

$wp->themeList();                    // GET theme/list
$wp->themeActivate('logitech');      // POST theme/activate
$wp->themeGetOptions();              // GET theme/options/get
$wp->themeUpdateOptions($options);   // POST theme/options/update
$wp->themeUpdateOption($name, $val); // Get all → merge → update
$wp->themeGetMeta();                 // GET theme/meta (schema)
$wp->themeOptionsReset();            // POST theme/options/reset
$wp->getPageUrl();                   // GET page/url
```

### Laravel Controller

**`Brand\WebsiteController`** handles theme editing UI:
- `themeOptions(GET)` — Hiển thị form edit
- `themeOptions(POST)` — Save từ form (recursive fillSchema)
- `themeOptionsReset(POST)` — Reset về default

**`Brand\WebsiteTemplateController`** handles theme marketplace:
- `index()` — List available templates
- `buy($uid)` — Purchase template
- `wpSetActive($theme)` — Activate theme

---

## 7. Page Templates — Cách Render

### 7.1 Template Registration

WordPress pages được tạo tự động khi theme activate. Mỗi page gắn với 1 template file:

```php
// functions.php → vbrand_shop_activate()
foreach($menus as $menu) {
    $page = vbrand_getOrCreatePageByTemplate($menu['type'], $menu['title']);
    // $menu['type'] = 'page-homepage.php', 'page-aboutus.php', etc.
}
```

Pages được tạo với meta:
- `_wp_page_template` = tên template file (e.g., `page-homepage.php`)
- `_theme_name` = tên theme hiện tại

### 7.2 Menu Rendering

Header lấy menus từ schema và resolve URL cho từng menu item:

```php
foreach ($themeData->get('menus') as $menu) {
    if ($menu['show'] == 'true') {
        // Tìm page tương ứng với template type
        $page = vbrand_getOrCreatePageByTemplate($menu['type']);
        $menuLink = get_permalink($page->ID);
        // Hoặc nếu type == 'shop' → dùng WooCommerce shop page
    }
}
```

### 7.3 Homepage Rendering Pattern

`page-homepage.php` render các section theo thứ tự:

```
1. Slider           ← $themeData->get('slider')
2. News             ← $themeData->get('news')
3. Products Tabs    ← $themeData->get('products_tabs') + WP_Query
4. Chuỗi Cửa Hàng  ← $themeData->get('shop_banner_one'), shop_banner_two, etc.
5. Mua Sắm SP      ← $themeData->get('shopping_banner_group')
6. Banner Slider    ← $themeData->get('bannerslider')
```

**Products Tabs** đặc biệt vì kết hợp schema data với WP_Query:
```php
foreach($themeData->get('products_tabs') as $products_tab) {
    $type = $products_tab['type'];   // 'new', 'hot', 'featured', 'all'
    $limit = $products_tab['limit']; // 5, 10, 15, 20

    // Build WP_Query based on type
    if ($type === 'new')      → orderby date DESC
    if ($type === 'hot')      → orderby total_sales DESC
    if ($type === 'featured') → tax_query product_visibility=featured

    // Render products using WooCommerce template
    wc_get_template_part('content', 'product');
}
```

### 7.4 Footer Rendering

`footer-home.php` render:
```php
// 4 widget areas
$themeData->get('footer_widget_1')  // Giới thiệu
$themeData->get('footer_widget_2')  // Giá trị
$themeData->get('footer_widget_3')  // Đối tác
$themeData->get('footer_widget_4')  // Khách hàng

// Social icons
$themeData->get('footer_social_icons')

// Copyright links
$themeData->get('footer_copyright_links')
```

---

## 8. Tham Chiếu Schema Hiện Tại (Logitech)

### Sessions
| Session | Title | Mô tả |
|---------|-------|--------|
| `general` | CHUNG | Site name, footer widgets, social icons, copyright |
| `menu` | MENU | Menu items + page template assignment |
| `home` | HOME | Slider, banners, products tabs, shop banners, news |
| `about-us` | ABOUT US | About Us module toggle |
| `partner` | Partner | (Chưa có options) |

### Complete Options Map

#### Session: `general`
| Name | Type | Default | Mô tả |
|------|------|---------|--------|
| `site_name` | text | "vBrand Theme One" | Tên website |
| `footer_widget_1` | list(label, url) | 6 links | Footer: Giới thiệu |
| `footer_widget_2` | list(label, url) | 3 links | Footer: Giá trị |
| `footer_widget_3` | list(label, url) | 4 links | Footer: Đối tác |
| `footer_widget_4` | list(label, url) | 1 link | Footer: Khách hàng |
| `footer_social_icons` | list(icon_class, url, title) | 5 icons | Social media links |
| `footer_copyright_links` | list(label, url) | 5 links | Copyright footer links |

#### Session: `menu`
| Name | Type | Schema | Default | Mô tả |
|------|------|--------|---------|--------|
| `menus` | list | show(boolean), title(text), type(select) | 5 menus | Navigation menu items |

**Menu type options:** `page-homepage.php`, `page-aboutus.php`, `shop`, `page-news.php`, `page-contact`

#### Session: `home`
| Name | Type | Schema | Max | Mô tả |
|------|------|--------|-----|--------|
| `slider` | list | anh(image), slidertitle(text), slidertitlesub(text), slidertitlesecond(text), slideralias(textarea), morebtn(text), morebtn_link(text), buybtn(text), buybtn_link(text) | 10 | Hero slider |
| `bannerslider` | list | banner(image), bannertitle(text), banneralias(textarea), morelink(text), moretitle(text) | 10 | Bottom banner slider |
| `news` | list | banner(image), bannertitle(text), banneralias(textarea), morelink(text), moretitle(text) | 10 | News section |
| `products_tabs` | list | tab_name(text), type(select: all/hot/featured/new), limit(select: 5/10/15/20) | 5 | Product tab groups |
| `products_module_show` | boolean | — | — | Toggle products module |
| `shop_module_show` | boolean | — | — | Toggle shop banners |
| `shop_banner_one` | image | — | — | Shop banner 1 image |
| `shop_banner_one_title` | text | — | — | Shop banner 1 title |
| `shop_banner_one_alias` | textarea | — | — | Shop banner 1 alias |
| `shop_banner_two` → `five` | image+text+textarea | — | — | Shop banners 2-5 (same pattern) |
| `shopping_banner_group` | list | image(image), title(text), link(text) | 10 | Product category banners |

#### Session: `about-us`
| Name | Type | Default | Mô tả |
|------|------|---------|--------|
| `about_us_show` | boolean | true | Toggle About Us module |

---

## 9. Hướng Dẫn Developer

### 9.1 Thêm Schema Field Mới

**Ví dụ: Thêm "Hotline" vào general session**

1. Mở `schema.php`, thêm vào `options` array:
```php
[
    'session' => 'general',
    'type'    => 'text',
    'name'    => 'hotline',
    'label'   => 'Số hotline',
    'default' => '0909.09.09.09',
],
```

2. Sử dụng trong template:
```php
$themeData = vbrand_load_theme_data();
echo $themeData->get('hotline');
```

3. Done. Laravel dashboard tự detect field mới từ schema.

### 9.2 Thêm Schema Component Mới (List Type)

**Ví dụ: Thêm "Testimonials" section**

1. Thêm session nếu cần (optional):
```php
'sessions' => [
    // ... existing
    ['name' => 'testimonials', 'title' => 'TESTIMONIALS'],
],
```

2. Thêm list option:
```php
[
    'session' => 'testimonials',  // hoặc 'home'
    'type'    => 'list',
    'name'    => 'testimonials',
    'label'   => 'Testimonials',
    'max'     => 10,
    'schema'  => [
        [
            'type'    => 'image',
            'name'    => 'avatar',
            'label'   => 'Ảnh đại diện',
            'default' => '',
        ],
        [
            'type'    => 'text',
            'name'    => 'name',
            'label'   => 'Tên khách hàng',
            'default' => '',
        ],
        [
            'type'    => 'textarea',
            'name'    => 'content',
            'label'   => 'Nội dung đánh giá',
            'default' => '',
        ],
        [
            'type'    => 'select',
            'name'    => 'rating',
            'label'   => 'Đánh giá',
            'default' => '5',
            'options' => [
                ['value' => '1', 'text' => '1 sao'],
                ['value' => '2', 'text' => '2 sao'],
                ['value' => '3', 'text' => '3 sao'],
                ['value' => '4', 'text' => '4 sao'],
                ['value' => '5', 'text' => '5 sao'],
            ],
        ],
    ],
    'default' => [
        [
            'avatar'  => get_template_directory_uri().'/assets/images/default-avatar.jpg',
            'name'    => 'Nguyễn Văn A',
            'content' => 'Sản phẩm rất tốt, dịch vụ chu đáo!',
            'rating'  => '5',
        ],
    ],
],
```

3. Render trong template:
```php
<?php $testimonials = $themeData->get('testimonials'); ?>
<?php if ($testimonials): ?>
    <?php foreach ($testimonials as $item): ?>
        <div class="testimonial">
            <img src="<?= $item['avatar'] ?>" alt="<?= esc_attr($item['name']) ?>">
            <h4><?= esc_html($item['name']) ?></h4>
            <p><?= esc_html($item['content']) ?></p>
            <span><?= str_repeat('★', (int)$item['rating']) ?></span>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
```

### 9.3 Tạo Page Template Mới

**Ví dụ: Thêm trang "Đội ngũ" (Team)**

1. Tạo file `page-team.php`:
```php
<?php
/**
 * Template Name: vBrand Team Page
 */
get_header();
$themeData = vbrand_load_theme_data();
?>

<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Đội Ngũ</h1>
    </div>
</div>

<?php $team = $themeData->get('team_members'); ?>
<?php if ($team): ?>
<div class="container">
    <div class="row">
        <?php foreach ($team as $member): ?>
            <div class="col-md-4">
                <img src="<?= $member['photo'] ?>">
                <h3><?= esc_html($member['name']) ?></h3>
                <p><?= esc_html($member['role']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>
```

2. Thêm schema cho team_members (xem 9.2)

3. Thêm menu option trong schema:
```php
// Trong menus option → schema → type select → options:
['value' => 'page-team.php', 'text' => 'Team'],
```

4. WordPress sẽ tự tạo page khi menu item chọn template này.

---

## 10. Tạo Theme Mới Từ Đầu

### Checklist

1. **Tạo thư mục theme**: `site/wp-content/themes/{theme-name}/`

2. **Bắt buộc có:**
   - `style.css` — Theme metadata header
   - `schema.php` — Schema definition
   - `functions.php` — WooCommerce support + menu setup
   - `screenshot.png` — Theme thumbnail

3. **Style.css header:**
```css
/*
Theme Name: My Brand Theme
Theme URI: https://vbrand.vn
Description: vBrand custom theme
Version: 1.0.0
Author: vBrand
*/
```

4. **Minimal schema.php:**
```php
<?php
return [
    'sessions' => [
        ['name' => 'general', 'title' => 'CHUNG'],
        ['name' => 'home',    'title' => 'HOME'],
    ],
    'options' => [
        [
            'session' => 'general',
            'type'    => 'text',
            'name'    => 'site_name',
            'label'   => 'Tên website',
            'default' => 'My Brand',
        ],
        [
            'session' => 'home',
            'type'    => 'list',
            'name'    => 'menus',
            'label'   => 'Menu',
            'max'     => 10,
            'schema'  => [
                ['type' => 'boolean', 'name' => 'show',  'label' => 'Hiển thị', 'default' => true],
                ['type' => 'text',    'name' => 'title', 'label' => 'Tên menu', 'default' => ''],
                ['type' => 'select',  'name' => 'type',  'label' => 'Template', 'default' => '', 'options' => [
                    ['value' => 'page-homepage.php', 'text' => 'Home'],
                    ['value' => 'shop',              'text' => 'Shop'],
                ]],
            ],
            'default' => [
                ['show' => true, 'title' => 'Trang chủ', 'type' => 'page-homepage.php'],
                ['show' => true, 'title' => 'Gian hàng', 'type' => 'shop'],
            ],
        ],
    ],
];
```

5. **Minimal functions.php:**
```php
<?php
add_theme_support('woocommerce');

function register_my_menu() {
    register_nav_menu('primary-menu', __('Primary Menu'));
}
add_action('after_setup_theme', 'register_my_menu');

// Auto-create pages from schema menus
$vbrand_setup = get_option('vbrand_' . get_template() . '_setup');
if (!$vbrand_setup) {
    $themeData = vbrand_load_theme_data();
    foreach ($themeData->get('menus') as $menu) {
        $page = vbrand_getOrCreatePageByTemplate($menu['type'], $menu['title']);
        if ($menu['type'] == 'shop') {
            update_option('woocommerce_shop_page_id', $page->ID);
        }
    }
    vbrand_setfrontPageByTemplate('page-homepage.php');
    update_option('vbrand_' . get_template() . '_setup', true);
}
```

6. **Tạo page templates** (`page-homepage.php`, etc.) sử dụng `$themeData->get()`

7. **Test:** Activate theme → Kiểm tra pages tự tạo → Edit options qua dashboard → Verify render

### Pattern Tái Sử Dụng

Khi tạo theme mới từ design (ví dụ từ hình), follow pattern:

1. **Phân tích layout** → Xác định sections cần schema
2. **Viết schema.php** → Mỗi section customizable = 1 option
3. **Viết page templates** → Dùng `$themeData->get()` cho mọi content
4. **Cung cấp default data** → Theme hoạt động ngay khi activate
5. **Test edit flow** → Dashboard → Edit → Save → Verify render

---

## Appendix: Logitech Theme — Toàn Bộ Schema Options

```
general/site_name            → text      → "vBrand Theme One"
menu/menus                   → list[10]  → [{show, title, type}]

home/slider                  → list[10]  → [{anh, slidertitle, slidertitlesub, slidertitlesecond, slideralias, morebtn, morebtn_link, buybtn, buybtn_link}]
home/bannerslider            → list[10]  → [{banner, bannertitle, banneralias, morelink, moretitle}]
home/news                    → list[10]  → [{banner, bannertitle, banneralias, morelink, moretitle}]
home/products_tabs           → list[5]   → [{tab_name, type, limit}]
home/products_module_show    → boolean   → true
home/shop_module_show        → boolean   → true
home/shop_banner_one         → image     → /assets/images/banners/shop-1.jpg
home/shop_banner_one_title   → text      → "DÒNG ERGO"
home/shop_banner_one_alias   → textarea  → ""
home/shop_banner_two         → image     → /assets/images/banners/shop-2.jpg
home/shop_banner_two_title   → text      → "DÒNG MX MASTER"
home/shop_banner_two_alias   → textarea  → ""
home/shop_banner_three       → image     → /assets/images/banners/shop-3.jpg
home/shop_banner_three_title → text      → "DÀNH CHO LẬP TRÌNH VIÊN"
home/shop_banner_three_alias → textarea  → ""
home/shop_banner_four        → image     → /assets/images/banners/shop-4.jpg
home/shop_banner_four_title  → text      → "DÀNH CHO CÔNG VIỆC SÁNG TẠO"
home/shop_banner_four_alias  → textarea  → ""
home/shop_banner_five        → image     → /assets/images/banners/shop-5.jpg
home/shop_banner_five_title  → text      → "SẴN SÀNG MANG THEO"
home/shop_banner_five_alias  → textarea  → ""
home/shopping_banner_group   → list[10]  → [{image, title, link}]

about-us/about_us_show       → boolean   → true

general/footer_widget_1      → list[10]  → [{label, url}]
general/footer_widget_2      → list[10]  → [{label, url}]
general/footer_widget_3      → list[10]  → [{label, url}]
general/footer_widget_4      → list[10]  → [{label, url}]
general/footer_social_icons  → list[10]  → [{icon_class, url, title}]
general/footer_copyright_links → list[10] → [{label, url}]
```
