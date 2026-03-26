<?php $__env->startSection('title', 'Edit: ' . $article->title); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.articles.index')); ?>">Admin</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.articles.index')); ?>">Articles</a></li>
    <li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Article</h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.articles.preview', $article)); ?>" class="btn btn-outline-secondary" target="_blank">
                <i class="bi bi-eye"></i> Preview
            </a>
            <?php if($article->status === 'published'): ?>
                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="btn btn-outline-success" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> View on Site
                </a>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?php echo e(route('admin.articles.update', $article)); ?>" method="POST" id="articleForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" value="<?php echo e(old('title', $article->title)); ?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" value="<?php echo e(old('slug', $article->slug)); ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="body_markdown" class="form-label">Body (Markdown)</label>
                            <textarea id="body_markdown" name="body_markdown" class="form-control"><?php echo e(old('body_markdown', $article->body_markdown)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Settings</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $article->category_id) == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content_type" class="form-label">Content Type</label>
                            <select id="content_type" name="content_type" class="form-select">
                                <option value="">-- Select Type --</option>
                                <option value="tutorial" <?php echo e(old('content_type', $article->content_type) === 'tutorial' ? 'selected' : ''); ?>>Tutorial</option>
                                <option value="guide" <?php echo e(old('content_type', $article->content_type) === 'guide' ? 'selected' : ''); ?>>Guide</option>
                                <option value="reference" <?php echo e(old('content_type', $article->content_type) === 'reference' ? 'selected' : ''); ?>>Reference</option>
                                <option value="comparison" <?php echo e(old('content_type', $article->content_type) === 'comparison' ? 'selected' : ''); ?>>Comparison</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">Difficulty</label>
                            <select id="difficulty" name="difficulty" class="form-select">
                                <option value="">-- Select Difficulty --</option>
                                <option value="beginner" <?php echo e(old('difficulty', $article->difficulty) === 'beginner' ? 'selected' : ''); ?>>Beginner</option>
                                <option value="intermediate" <?php echo e(old('difficulty', $article->difficulty) === 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                                <option value="advanced" <?php echo e(old('difficulty', $article->difficulty) === 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="draft" <?php echo e(old('status', $article->status) === 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="published" <?php echo e(old('status', $article->status) === 'published' ? 'selected' : ''); ?>>Published</option>
                                <option value="archived" <?php echo e(old('status', $article->status) === 'archived' ? 'selected' : ''); ?>>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label">Published At</label>
                            <input type="datetime-local" id="published_at" name="published_at" value="<?php echo e(old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '')); ?>" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" rows="3"><?php echo e(old('excerpt', $article->excerpt)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(isset($tags) && $tags->count() > 0): ?>
            <?php
                $articleTagIds = old('tags', $article->tags->pluck('id')->toArray());
            ?>
            <div class="card mb-4">
                <div class="card-header">Tags</div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="tags[]" value="<?php echo e($tag->id); ?>" id="tag_<?php echo e($tag->id); ?>" <?php echo e(in_array($tag->id, $articleTagIds) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="tag_<?php echo e($tag->id); ?>"><?php echo e($tag->name); ?></label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="card mb-4">
            <div class="card-header">Meta Fields</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="<?php echo e(old('meta_title', $article->meta_title)); ?>" class="form-control" placeholder="SEO title (defaults to article title)">
                </div>
                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" rows="2" placeholder="SEO description (defaults to excerpt)"><?php echo e(old('meta_description', $article->meta_description)); ?></textarea>
                </div>
            </div>
        </div>

        
        <div class="d-flex gap-2 mb-4">
            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                <i class="bi bi-file-earmark"></i> Save as Draft
            </button>
            <button type="submit" name="status" value="published" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Publish
            </button>
            <a href="<?php echo e(route('admin.articles.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        // Initialize EasyMDE
        const easyMDE = new EasyMDE({
            element: document.getElementById('body_markdown'),
            spellChecker: false,
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'code', '|',
                'unordered-list', 'ordered-list', '|',
                'link', 'image', 'table', 'horizontal-rule', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            minHeight: '400px',
        });

        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/-$/, '');
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = 'false';
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/admin/articles/edit.blade.php ENDPATH**/ ?>