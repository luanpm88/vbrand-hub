<?php $__env->startSection('title', ($query ?? '') ? '"' . $query . '" — Search — AcelleMail KB' : 'Search — AcelleMail KB'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="kb-search-header">
        <h1>Search Articles</h1>
        <?php if(!empty($query)): ?>
            <p><?php echo e($articles->total()); ?> <?php echo e(Str::plural('result', $articles->total())); ?> for "<?php echo e($query); ?>"</p>
        <?php else: ?>
            <p>Find tutorials, guides, and references.</p>
        <?php endif; ?>
    </div>

    <form action="<?php echo e(route('articles.search')); ?>" method="GET" class="kb-search-form">
        <svg class="kb-search-form__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="q" value="<?php echo e($query ?? ''); ?>" placeholder="Search tutorials, guides, references..." autofocus autocomplete="off">
    </form>

    
    <?php if(isset($articles)): ?>
        <div class="kb-grid-3">
            <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php if(!empty($query)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                        <p class="text-muted" style="font-size: 1.125rem; margin-bottom: 8px;">No results found for "<?php echo e($query); ?>"</p>
                        <p class="text-muted text-small">Try different keywords or browse by category.</p>
                        <a href="<?php echo e(route('home')); ?>" class="kb-btn kb-btn--secondary mt-24">Browse All Articles</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if($articles->count() > 0): ?>
            <div class="kb-pagination">
                <?php echo e($articles->appends(['q' => $query])->links()); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/articles/search.blade.php ENDPATH**/ ?>