
<article class="kb-card">
    <?php if($article->category): ?>
        <a href="<?php echo e(route('articles.category', $article->category->slug)); ?>">
            <span class="kb-badge kb-badge--category" style="background-color: <?php echo e($article->category->color ?? '#E8571A'); ?>;">
                <?php echo e($article->category->name); ?>

            </span>
        </a>
    <?php endif; ?>

    <h3 class="kb-card__title" style="margin-top: 10px;">
        <a href="<?php echo e(route('articles.show', $article->slug)); ?>">
            <?php echo e($article->title); ?>

        </a>
    </h3>

    <?php if($article->excerpt): ?>
        <p class="kb-card__excerpt"><?php echo e($article->excerpt); ?></p>
    <?php endif; ?>

    <div class="kb-card__meta">
        <?php if($article->content_type): ?>
            <span><?php echo e(ucfirst($article->content_type)); ?></span>
            <span class="kb-card__meta-sep"></span>
        <?php endif; ?>

        <?php if($article->reading_time): ?>
            <span><?php echo e($article->reading_time); ?> min read</span>
            <span class="kb-card__meta-sep"></span>
        <?php endif; ?>

        <?php if($article->published_at): ?>
            <span><?php echo e($article->published_at->format('M d, Y')); ?></span>
        <?php endif; ?>
    </div>
</article>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/article-card.blade.php ENDPATH**/ ?>