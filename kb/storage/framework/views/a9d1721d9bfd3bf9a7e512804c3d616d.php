
<div class="kb-tags-section__list">
    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('articles.tag', $tag->slug)); ?>" class="kb-tag">
            <?php echo e($tag->name); ?>

        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/popular-tags.blade.php ENDPATH**/ ?>