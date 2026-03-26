
<?php if($article->author_name): ?>
<div class="kb-author">
    <div class="kb-author__avatar">
        <?php if($article->author_avatar): ?>
            <img src="<?php echo e(asset('images/' . $article->author_avatar)); ?>" alt="<?php echo e($article->author_name); ?>">
        <?php else: ?>
            <?php echo e(strtoupper(substr($article->author_name, 0, 1))); ?>

        <?php endif; ?>
    </div>
    <div class="kb-author__info">
        <h4><?php echo e($article->author_name); ?></h4>
        <?php if($article->author_bio): ?>
            <p><?php echo e($article->author_bio); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/author-bio.blade.php ENDPATH**/ ?>