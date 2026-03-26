<?php $__env->startSection('title', ($article->meta_title ?: $article->title) . ' — AcelleMail KB'); ?>
<?php $__env->startSection('meta_description', $article->meta_description ?: $article->excerpt); ?>

<?php $__env->startSection('content'); ?>
    
    <?php echo $__env->make('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $article->category->name ?? 'Uncategorized', 'url' => $article->category ? route('articles.category', $article->category->slug) : route('home')],
        ['label' => $article->title, 'url' => '#'],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="kb-article-header">
        <?php if($article->category): ?>
            <a href="<?php echo e(route('articles.category', $article->category->slug)); ?>">
                <span class="kb-badge kb-badge--category" style="background-color: <?php echo e($article->category->color ?? '#E8571A'); ?>;">
                    <?php echo e($article->category->name); ?>

                </span>
            </a>
        <?php endif; ?>

        <h1><?php echo e($article->title); ?></h1>

        <div class="kb-article-header__meta">
            <?php if($article->published_at): ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?php echo e($article->published_at->format('F d, Y')); ?>

                </span>
            <?php endif; ?>

            <?php if($article->reading_time): ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <?php echo e($article->reading_time); ?> min read
                </span>
            <?php endif; ?>

            <?php if($article->views_count): ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <?php echo e(number_format($article->views_count)); ?> views
                </span>
            <?php endif; ?>

            <?php if($article->content_type): ?>
                <span>
                    <?php echo e(ucfirst($article->content_type)); ?>

                </span>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="kb-article-layout">
        <div class="kb-article-layout__content">
            
            <div class="kb-prose">
                <?php echo $article->body_html; ?>

            </div>

            
            <?php if($article->tags->count() > 0): ?>
                <div class="kb-tags-section">
                    <h4 class="kb-tags-section__title">Tags</h4>
                    <div class="kb-tags-section__list">
                        <?php $__currentLoopData = $article->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('articles.tag', $tag->slug)); ?>" class="kb-tag"><?php echo e($tag->name); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php echo $__env->make('partials.author-bio', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php if(isset($relatedArticles) && $relatedArticles->count() > 0): ?>
                <div class="kb-section mt-48">
                    <div class="kb-section__header">
                        <h2 class="kb-section__title">Related Articles</h2>
                    </div>
                    <div class="kb-grid-3">
                        <?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('partials.article-card', ['article' => $related], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        
        <aside class="kb-article-layout__sidebar">
            <?php echo $__env->make('partials.toc', ['toc' => $toc ?? []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Re-run Prism highlighting after page load
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.kb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/articles/show.blade.php ENDPATH**/ ?>