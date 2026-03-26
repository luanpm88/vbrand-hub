
<?php if(!empty($toc) && count($toc) > 0): ?>
<div class="kb-toc">
    <h4 class="kb-toc__title">Table of Contents</h4>
    <ul class="kb-toc__list">
        <?php $__currentLoopData = $toc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="#<?php echo e($item['id']); ?>" class="<?php echo e($item['level'] === 3 ? 'kb-toc__h3' : ''); ?>">
                    <?php echo e($item['text']); ?>

                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tocLinks = document.querySelectorAll('.kb-toc__list a');
    if (tocLinks.length === 0) return;

    const headings = [];
    tocLinks.forEach(function(link) {
        const id = link.getAttribute('href').substring(1);
        const el = document.getElementById(id);
        if (el) headings.push({ el: el, link: link });
    });

    function updateActive() {
        let current = null;
        const scrollTop = window.scrollY + 100;

        headings.forEach(function(h) {
            if (h.el.offsetTop <= scrollTop) {
                current = h;
            }
        });

        tocLinks.forEach(function(l) { l.classList.remove('active'); });
        if (current) current.link.classList.add('active');
    }

    window.addEventListener('scroll', updateActive, { passive: true });
    updateActive();
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/luan/apps/vbrand/kb/resources/views/partials/toc.blade.php ENDPATH**/ ?>