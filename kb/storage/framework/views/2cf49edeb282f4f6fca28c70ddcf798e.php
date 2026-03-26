<?php $__env->startSection('title', 'Manage Articles — AcelleMail KB Admin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex justify-between items-center mb-32">
        <h1>Manage Articles</h1>
        <a href="<?php echo e(route('admin.articles.create')); ?>" class="kb-btn kb-btn--primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Article
        </a>
    </div>

    <?php if(session('success')): ?>
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.9375rem;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <table class="kb-admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Type</th>
                <th>Status</th>
                <th>Published</th>
                <th style="width: 140px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>" style="font-weight: 500; color: #241C15;">
                            <?php echo e($article->title); ?>

                        </a>
                    </td>
                    <td>
                        <?php if($article->category): ?>
                            <span class="kb-badge kb-badge--category" style="background-color: <?php echo e($article->category->color ?? '#E8571A'); ?>;">
                                <?php echo e($article->category->name); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-muted">--</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($article->content_type): ?>
                            <span class="text-small"><?php echo e(ucfirst($article->content_type)); ?></span>
                        <?php else: ?>
                            <span class="text-muted">--</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $statusClass = match($article->status) {
                                'published' => 'kb-badge--published',
                                'archived' => 'kb-badge--archived',
                                default => 'kb-badge--draft',
                            };
                        ?>
                        <span class="kb-badge kb-badge--status <?php echo e($statusClass); ?>">
                            <?php echo e(ucfirst($article->status)); ?>

                        </span>
                    </td>
                    <td>
                        <span class="text-small text-muted">
                            <?php echo e($article->published_at ? $article->published_at->format('M d, Y') : '--'); ?>

                        </span>
                    </td>
                    <td>
                        <div class="kb-admin-table__actions">
                            <a href="<?php echo e(route('admin.articles.edit', $article)); ?>" class="kb-btn kb-btn--secondary kb-btn--sm">Edit</a>

                            <form action="<?php echo e(route('admin.articles.destroy', $article)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="kb-btn kb-btn--danger kb-btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px 16px; color: #6E6860;">
                        No articles yet. <a href="<?php echo e(route('admin.articles.create')); ?>">Create your first article.</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if(method_exists($articles, 'links')): ?>
        <div class="kb-pagination">
            <?php echo e($articles->links()); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/admin/articles/index.blade.php ENDPATH**/ ?>