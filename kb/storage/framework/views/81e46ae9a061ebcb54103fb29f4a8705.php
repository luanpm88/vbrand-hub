<?php $__env->startSection('title', 'AcelleMail Knowledge Base — Tutorials, Guides & References'); ?>
<?php $__env->startSection('meta_description', 'Learn everything about AcelleMail — self-hosted email marketing. Tutorials, guides, references, and best practices.'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="kb-hero">
        <h1>AcelleMail Knowledge Base</h1>
        <p>Tutorials, guides, and references for building and managing your self-hosted email marketing platform.</p>

        <form action="<?php echo e(route('articles.search')); ?>" method="GET" class="kb-hero__search">
            <svg class="kb-hero__search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" placeholder="Search tutorials, guides, references..." autocomplete="off">
        </form>
    </div>

    
    <div class="kb-section">
        <div class="kb-section__header">
            <h2 class="kb-section__title">Browse by Category</h2>
        </div>

        <?php echo $__env->make('partials.category-grid', ['categories' => $categories], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <?php echo $__env->make('partials.filter-bar', ['categories' => $categories, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="kb-sidebar-layout">
        <div class="kb-sidebar-layout__main">
            
            <?php if($featuredArticles->count() > 0 && $articles->currentPage() === 1): ?>
                <div class="kb-section">
                    <div class="kb-section__header">
                        <h2 class="kb-section__title">Featured</h2>
                    </div>
                    <div class="kb-grid-3">
                        <?php $__currentLoopData = $featuredArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="kb-section">
                <div class="kb-section__header">
                    <h2 class="kb-section__title">All Articles</h2>
                    <span class="text-muted text-small"><?php echo e($articles->total()); ?> <?php echo e(Str::plural('article', $articles->total())); ?></span>
                </div>
                <div class="kb-grid-3">
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                            <p class="text-muted">No articles found matching your filters.</p>
                            <a href="<?php echo e(route('home')); ?>" class="kb-btn kb-btn--secondary mt-16">Clear Filters</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="kb-pagination">
                    <?php echo e($articles->links()); ?>

                </div>
            </div>
        </div>

        
        <aside class="kb-sidebar-layout__aside">
            
            <div class="kb-widget">
                <h4 class="kb-widget__title">Categories</h4>
                <ul class="kb-widget__list">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('articles.category', $cat->slug)); ?>">
                                <?php echo e($cat->name); ?>

                                <span class="count"><?php echo e($cat->articles_count ?? $cat->articles()->count()); ?></span>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            
            <?php if(isset($popularTags) && $popularTags->count() > 0): ?>
                <div class="kb-widget">
                    <h4 class="kb-widget__title">Popular Tags</h4>
                    <?php echo $__env->make('partials.popular-tags', ['tags' => $popularTags], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/articles/index.blade.php ENDPATH**/ ?>