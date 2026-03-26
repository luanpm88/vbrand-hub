<?php $__env->startSection('title', 'Categories'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active">Categories</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Categories</h1>
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Category
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">Color</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Group</th>
                        <th>Articles</th>
                        <th>Sort Order</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div style="width: 24px; height: 24px; border-radius: 4px; background-color: <?php echo e($category->color ?? '#6c757d'); ?>;" title="<?php echo e($category->color); ?>"></div>
                            </td>
                            <td class="fw-medium">
                                <?php if($category->icon): ?>
                                    <i class="bi bi-<?php echo e($category->icon); ?> me-1"></i>
                                <?php endif; ?>
                                <?php echo e($category->name); ?>

                            </td>
                            <td><code><?php echo e($category->slug); ?></code></td>
                            <td>
                                <?php if($category->group): ?>
                                    <span class="badge bg-info"><?php echo e($category->group); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">--</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($category->articles_count ?? $category->articles->count()); ?></td>
                            <td><?php echo e($category->sort_order ?? 0); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No categories yet. <a href="<?php echo e(route('admin.categories.create')); ?>">Create your first category.</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>