<?php if(is_cms()): ?>
    <?php echo $__env->make('templates.admin.partials.backend_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php else: ?>
    <?php echo $__env->make('templates.admin.partials.published_content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>