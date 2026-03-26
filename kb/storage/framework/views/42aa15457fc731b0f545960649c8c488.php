<?php $__env->startSection('title', '#' . $tag->name . ' — AcelleMail KB'); ?>
<?php $__env->startSection('meta_description', 'Articles tagged with "' . $tag->name . '" on AcelleMail Knowledge Base.'); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => '#' . $tag->name, 'url' => '#'],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="kb-search-header">
        <h1>
            <span class="kb-tag" style="font-size: 1rem; padding: 6px 16px; pointer-events: none;"><?php echo e($tag->name); ?></span>
        </h1>
        <p class="mt-8"><?php echo e($articles->total()); ?> <?php echo e(Str::plural('article', $articles->total())); ?> tagged with "<?php echo e($tag->name); ?>"</p>
    </div>

    
    <div class="kb-grid-3">
        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                <p class="text-muted">No articles with this tag yet.</p>
                <a href="<?php echo e(route('home')); ?>" class="kb-btn kb-btn--secondary mt-16">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="kb-pagination">
        <?php echo e($articles->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/articles/tag.blade.php ENDPATH**/ ?>