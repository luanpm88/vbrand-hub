# AcelleMail KB — Code Architecture

## 4-Layer Architecture

```
Request → Controller → Service → Model/DTO
                    ↓
              Event → Listener (side effects)
```

### Layer Responsibilities

| Layer | Purpose | Rules |
|-------|---------|-------|
| **Controller** | HTTP handling, validation | Never touches DB directly. Calls Service methods. Returns views. |
| **Service** | Business logic | DB operations, caching, event dispatching, markdown compilation. |
| **Model** | Data + relationships | Eloquent models with scopes. No business logic. |
| **DTO** | Data transformation | Static methods transforming models to arrays for views. |
| **Event/Listener** | Side effects | Cache invalidation, view counting. Dispatched from Services. |
| **FormRequest** | Validation | All input validation rules. Used by Controllers. |

## Directory Structure

```
kb/
├── app/
│   ├── DTOs/
│   │   ├── ArticleDTO.php        → card(), detail(), admin()
│   │   ├── CategoryDTO.php       → summary()
│   │   └── TagDTO.php            → summary()
│   ├── Events/
│   │   ├── ArticlePublished.php  → dispatched on publish
│   │   └── ArticleViewed.php     → dispatched on page view
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ArticleController.php       → public pages (index, show, category, tag, search)
│   │   │   ├── SitemapController.php       → XML sitemap
│   │   │   └── Admin/
│   │   │       ├── ArticleController.php   → CRUD + preview
│   │   │       ├── CategoryController.php  → CRUD
│   │   │       └── TagController.php       → CRUD
│   │   └── Requests/
│   │       ├── StoreArticleRequest.php
│   │       ├── UpdateArticleRequest.php
│   │       └── SearchRequest.php
│   ├── Listeners/
│   │   ├── ClearCacheListener.php      → clears sitemap/category cache
│   │   └── IncrementViewsListener.php  → session-debounced view counter
│   ├── Models/
│   │   ├── Article.php  → scopes: published(), ofCategory(), ofContentType()
│   │   ├── Category.php → scope: ordered()
│   │   └── Tag.php      → scope: popular()
│   ├── Providers/
│   │   └── AppServiceProvider.php → event registration
│   └── Services/
│       ├── ArticleService.php    → CRUD, filtering, markdown, TOC, views
│       ├── CategoryService.php   → CRUD, ordered listing
│       ├── MarkdownService.php   → league/commonmark, heading permalinks, TOC
│       ├── SearchService.php     → LIKE-based full-text search
│       ├── SitemapService.php    → XML sitemap generation
│       └── TagService.php        → CRUD
├── database/
│   ├── migrations/               → 6 migration files
│   └── seeders/
│       ├── DatabaseSeeder.php    → orchestrator
│       ├── CategorySeeder.php    → 18 categories in 4 groups
│       ├── TagSeeder.php         → 97 tags
│       ├── ArticleSeeder.php     → 21 base articles
│       ├── ArticleBatch1Seeder.php → 22 additional articles
│       ├── ArticleBatch2Seeder.php → 15 infrastructure articles
│       └── ArticleBatch3Seeder.php → 20 advanced articles
├── resources/views/
│   ├── layouts/
│   │   ├── kb.blade.php          → public layout (inline CSS, Fraunces/IBM Plex Sans)
│   │   └── admin.blade.php       → admin layout (Bootstrap 5, dark/light mode)
│   ├── articles/                 → public article pages
│   ├── partials/                 → public reusable components
│   ├── admin/                    → admin CRUD views (Bootstrap 5)
│   └── auth/                     → login page (standalone Bootstrap)
├── routes/
│   ├── web.php                   → public + admin routes
│   └── auth.php                  → Breeze auth routes
└── public/
    ├── css/admin.css             → Bootstrap variable overrides
    └── images/                   → logos, icons
```

## Key Patterns

### Adding a New Article Programmatically
```php
$article = app(ArticleService::class)->storeArticle([
    'title' => 'My Article',
    'body_markdown' => '## Hello\n\nContent here.',
    'category_id' => 1,
    'status' => 'published',
    'content_type' => 'tutorial',
    'published_at' => now(),
]);
```

### Markdown to HTML
```php
$html = app(MarkdownService::class)->toHtml($markdown);
$toc = app(MarkdownService::class)->generateToc($html);
$readingTime = app(MarkdownService::class)->calculateReadingTime($markdown);
```

### Filtering Articles
```php
$articles = app(ArticleService::class)->getPublishedArticles([
    'category' => 'email-marketing',
    'content_type' => 'tutorial',
    'sort' => 'popular',
]);
```

## Database Schema

### Core Tables
- `articles` — main content (title, slug, body_markdown, body_html, status, category_id)
- `categories` — 18 categories in 4 groups (getting-started, infrastructure, advanced, resources)
- `tags` — 97 tags, many-to-many with articles
- `article_tag` — pivot table
- `article_related` — self-referencing pivot for related articles

### Article Status Flow
```
draft → published → archived
         ↑            ↓
         └────────────┘ (can unpublish/republish)
```
