<?php $__env->startSection('title', 'Articles'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active">Articles</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Articles</h1>
        <a href="<?php echo e(route('admin.articles.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Article
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('admin.articles.edit', $article)); ?>" class="fw-medium text-decoration-none">
                                    <?php echo e($article->title); ?>

                                </a>
                            </td>
                            <td>
                                <?php if($article->category): ?>
                                    <span class="badge" style="background-color: <?php echo e($article->category->color ?? '#6c757d'); ?>;">
                                        <?php echo e($article->category->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">--</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $statusBg = match($article->status) {
                                        'published' => 'bg-success',
                                        'archived' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                ?>
                                <span class="badge <?php echo e($statusBg); ?>"><?php echo e(ucfirst($article->status)); ?></span>
                            </td>
                            <td>
                                <?php if($article->content_type): ?>
                                    <?php echo e(ucfirst($article->content_type)); ?>

                                <?php else: ?>
                                    <span class="text-muted">--</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(number_format($article->views ?? 0)); ?></td>
                            <td>
                                <small class="text-muted">
                                    <?php echo e($article->published_at ? $article->published_at->format('M d, Y') : '--'); ?>

                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo e(route('admin.articles.edit', $article)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.articles.destroy', $article)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this article?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No articles yet. <a href="<?php echo e(route('admin.articles.create')); ?>">Create your first article.</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($articles->hasPages()): ?>
        <div class="mt-3">
            <?php echo e($articles->links()); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/admin/articles/index.blade.php ENDPATH**/ ?>