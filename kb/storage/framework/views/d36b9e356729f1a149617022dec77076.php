
<div class="kb-grid-3">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('articles.category', $category->slug)); ?>" class="kb-cat-card">
            <div class="kb-cat-card__icon" style="background-color: <?php echo e($category->color ?? '#E8571A'); ?>20;">
                <span><?php echo e($category->icon ?? '📁'); ?></span>
            </div>
            <div class="kb-cat-card__info">
                <h3><?php echo e($category->name); ?></h3>
                <span><?php echo e($category->articles_count ?? $category->articles()->count()); ?> <?php echo e(Str::plural('article', $category->articles_count ?? $category->articles()->count())); ?></span>
            </div>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/category-grid.blade.php ENDPATH**/ ?>