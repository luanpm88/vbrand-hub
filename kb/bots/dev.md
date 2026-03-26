# AcelleMail Knowledge Base — Dev Bot

> Full development bot for the AcelleMail KB site.
> Laravel 12, 4-layer architecture (DTOs, Services, Requests, Events).

## Usage

```bash
kb/bots/dev.md                          # General dev tasks
kb/bots/dev.md "add article about X"    # Create new article
kb/bots/dev.md "fix layout issue"       # Fix UI/code
kb/bots/dev.md deploy                   # Deploy to server
kb/bots/dev.md audit                    # Screenshot all pages
kb/bots/dev.md seed                     # Re-seed database
```

## Project Structure

```
kb/                              → Laravel 12 Knowledge Base
├── app/
│   ├── DTOs/                    → ArticleDTO, CategoryDTO, TagDTO
│   ├── Services/                → ArticleService, CategoryService, SearchService, MarkdownService, SitemapService
│   ├── Http/
│   │   ├── Controllers/         → ArticleController (public), Admin/ArticleController (CRUD)
│   │   └── Requests/            → StoreArticleRequest, UpdateArticleRequest, SearchRequest
│   ├── Events/                  → ArticlePublished, ArticleViewed
│   ├── Listeners/               → ClearCacheListener, IncrementViewsListener
│   └── Models/                  → Article, Category, Tag
├── database/
│   ├── migrations/              → 5 tables
│   └── seeders/                 → CategorySeeder, TagSeeder, ArticleSeeder
├── resources/views/
│   ├── layouts/app.blade.php    → Main layout (inline CSS, fonts, Prism.js)
│   ├── articles/                → index, show, category, tag, search
│   ├── partials/                → header, footer, article-card, toc, filter-bar, breadcrumbs
│   └── admin/                   → CRUD forms
├── routes/web.php               → All routes
├── CONTENT.md                   → Full knowledge reference
└── bots/dev.md                  → This file
```

## Architecture Rules

### 4-Layer Pattern
1. **Controller** → validates via FormRequest, calls Service, returns view
2. **Service** → business logic, caching, events, DB operations
3. **DTO** → transforms Eloquent models to structured arrays for views
4. **Event/Listener** → side effects (cache clear, view counting)

### Code Patterns
- Controllers NEVER touch DB directly — always through Services
- Services use Eloquent models + scopes
- DTOs have static methods: `card()`, `detail()`, `admin()`, `summary()`
- Events dispatched from Services, not Controllers
- FormRequests handle ALL validation

### Models
- `Article`: status (draft/published/archived), content_type (tutorial/guide/reference/comparison), difficulty (beginner/intermediate/advanced)
- `Category`: 15 categories, ordered by sort_order
- `Tag`: many-to-many with articles

### Routes
| Method | URL | Name | Controller |
|--------|-----|------|-----------|
| GET | / | home | ArticleController@index |
| GET | /articles/{slug} | articles.show | ArticleController@show |
| GET | /category/{slug} | articles.category | ArticleController@category |
| GET | /tag/{slug} | articles.tag | ArticleController@tag |
| GET | /search | articles.search | ArticleController@search |
| GET | /sitemap.xml | sitemap | SitemapController@index |
| CRUD | /admin/articles/* | admin.articles.* | Admin\ArticleController |

## Design System

### Colors
| Variable | Value | Usage |
|----------|-------|-------|
| --kb-orange | #E8571A | Primary accent, buttons, links, badges |
| --kb-black | #241C15 | Text, headings, header bg |
| --kb-cream | #FEF7F2 | Page background, hero sections |
| --kb-light-gray | #F6F1EB | Card backgrounds, alternating rows |
| --kb-gray | #6E6860 | Secondary text, meta info |
| --kb-border | #E5E0DA | Borders, dividers |

### Fonts
- Headings: `Fraunces` (serif, optical sizing)
- Body: `IBM Plex Sans` (sans-serif)
- Code: `IBM Plex Mono` / system monospace

### Layout
- Container: max-width 1200px
- Article body: max-width 720px
- Sidebar: 280px sticky
- Grid: 3-column cards on desktop, 1 on mobile
- Article detail: 2-column (content + TOC sidebar)

## Content Guidelines

### Article Markdown
- Use h2 (##) for main sections, h3 (###) for subsections
- Code blocks with language: ```php, ```bash, ```nginx, ```yaml
- Tables for comparison/reference data
- Callout blocks: > **Note:** for tips, > **Warning:** for cautions
- Include practical examples, not just theory
- Reading time auto-calculated (body word count / 200 wpm)

### Categories (15)
1. Email Marketing  2. Automation  3. Sending & Deliverability
4. List Management  5. Analytics & Reporting  6. Integrations
7. Security & Compliance  8. Installation & Setup  9. SaaS & Multi-tenant
10. Developer Guide  11. DNS & Domain Setup  12. Server Management
13. Troubleshooting  14. Best Practices  15. Migration & Comparison

### Content Types
- **Tutorial**: Step-by-step how-to (beginner-friendly)
- **Guide**: In-depth explanation of concepts
- **Reference**: Quick-lookup tables, configs, lists
- **Comparison**: Side-by-side comparisons (tools, approaches)

## Common Tasks

### Add new article via seeder
```php
// database/seeders/ArticleSeeder.php
Article::create([
    'title' => 'How to Configure Amazon SES',
    'slug' => 'configure-amazon-ses',
    'excerpt' => 'Step-by-step guide to setting up Amazon SES...',
    'body_markdown' => $markdown,
    'body_html' => app(MarkdownService::class)->toHtml($markdown),
    'category_id' => Category::where('slug', 'sending-deliverability')->first()->id,
    'status' => 'published',
    'content_type' => 'tutorial',
    'difficulty' => 'intermediate',
    'published_at' => now(),
]);
```

### Compile markdown for all articles
```bash
php artisan tinker
>>> App\Models\Article::all()->each(function($a) { $a->update(['body_html' => app(App\Services\MarkdownService::class)->toHtml($a->body_markdown)]); });
```

### Local dev
```bash
cd kb && php artisan serve --port=8090
# Visit http://localhost:8090
```

### Deploy
```bash
rsync -avz --rsync-path="sudo rsync" \
  --exclude='.git/' --exclude='vendor/' --exclude='node_modules/' \
  --exclude='.env' --exclude='.DS_Store' --exclude='database/database.sqlite' \
  kb/ brandnew:/var/www/kb-acellemail/

ssh brandnew "cd /var/www/kb-acellemail && \
  sudo -u vbrandwww composer install --no-dev --optimize-autoloader && \
  sudo -u vbrandwww php artisan config:cache && \
  sudo -u vbrandwww php artisan route:cache && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/kb-acellemail"
```

### Audit (screenshot all pages)
```bash
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
BASE="https://kb.acellemail.com"
DIR="kb/design/audit_$(date +%Y%m%d)"
mkdir -p "$DIR"

for page in "" "category/email-marketing" "articles/configure-amazon-ses" "search?q=smtp"; do
  name=$(echo "$page" | tr '/' '-' | tr '?' '-')
  [ -z "$name" ] && name="home"
  "$CHROME" --headless --disable-gpu --screenshot="$DIR/${name}_desktop.png" --window-size=1440,5000 "$BASE/$page" 2>/dev/null
  echo "✓ $name"
done
```

## Reference Files

| File | Purpose |
|------|---------|
| `CONTENT.md` | Full email marketing knowledge base reference |
| `app/Services/ArticleService.php` | Core business logic |
| `app/Services/MarkdownService.php` | Markdown → HTML + TOC |
| `app/Models/Article.php` | Central model with scopes |
| `resources/views/layouts/app.blade.php` | Full layout + CSS |
| `resources/views/articles/show.blade.php` | Article detail (2-col) |
| `database/seeders/ArticleSeeder.php` | Sample articles |
