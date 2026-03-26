<?php $__env->startSection('title', $category->name . ' — AcelleMail KB'); ?>
<?php $__env->startSection('meta_description', $category->description ?: 'Browse ' . $category->name . ' articles on AcelleMail Knowledge Base.'); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $category->name, 'url' => '#'],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="kb-category-header">
        <div class="kb-category-header__icon" style="background-color: <?php echo e($category->color ?? '#E8571A'); ?>20;">
            <span><?php echo e($category->icon ?? '📁'); ?></span>
        </div>
        <div class="kb-category-header__info">
            <h1><?php echo e($category->name); ?></h1>
            <?php if($category->description): ?>
                <p><?php echo e($category->description); ?></p>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="kb-grid-3">
        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                <p class="text-muted">No articles in this category yet.</p>
                <a href="<?php echo e(route('home')); ?>" class="kb-btn kb-btn--secondary mt-16">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="kb-pagination">
        <?php echo e($articles->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/articles/category.blade.php ENDPATH**/ ?>