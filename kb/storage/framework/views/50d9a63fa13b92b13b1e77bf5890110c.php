
<form action="<?php echo e(url()->current()); ?>" method="GET" class="kb-filter-bar">
    <span class="kb-filter-bar__label">Filter:</span>

    
    <select name="category" class="kb-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cat->slug); ?>" <?php echo e(($filters['category'] ?? '') === $cat->slug ? 'selected' : ''); ?>>
                <?php echo e($cat->name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    
    <select name="content_type" class="kb-select" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="tutorial" <?php echo e(($filters['content_type'] ?? '') === 'tutorial' ? 'selected' : ''); ?>>Tutorial</option>
        <option value="guide" <?php echo e(($filters['content_type'] ?? '') === 'guide' ? 'selected' : ''); ?>>Guide</option>
        <option value="reference" <?php echo e(($filters['content_type'] ?? '') === 'reference' ? 'selected' : ''); ?>>Reference</option>
        <option value="comparison" <?php echo e(($filters['content_type'] ?? '') === 'comparison' ? 'selected' : ''); ?>>Comparison</option>
    </select>

    
    <select name="sort" class="kb-select" onchange="this.form.submit()">
        <option value="newest" <?php echo e(($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : ''); ?>>Newest First</option>
        <option value="oldest" <?php echo e(($filters['sort'] ?? '') === 'oldest' ? 'selected' : ''); ?>>Oldest First</option>
        <option value="popular" <?php echo e(($filters['sort'] ?? '') === 'popular' ? 'selected' : ''); ?>>Most Popular</option>
    </select>

    <?php if(!empty($filters['category']) || !empty($filters['content_type']) || ($filters['sort'] ?? 'newest') !== 'newest'): ?>
        <a href="<?php echo e(url()->current()); ?>" class="kb-btn kb-btn--secondary kb-btn--sm">Clear Filters</a>
    <?php endif; ?>
</form>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/filter-bar.blade.php ENDPATH**/ ?>