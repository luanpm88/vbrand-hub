
<nav class="kb-breadcrumbs" aria-label="Breadcrumb">
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($i > 0): ?>
            <span class="kb-breadcrumbs__sep">/</span>
        <?php endif; ?>

        <?php if($i === count($items) - 1): ?>
            <span class="kb-breadcrumbs__current"><?php echo e($item['label']); ?></span>
        <?php else: ?>
            <a href="<?php echo e($item['url']); ?>"><?php echo e($item['label']); ?></a>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/breadcrumbs.blade.php ENDPATH**/ ?>