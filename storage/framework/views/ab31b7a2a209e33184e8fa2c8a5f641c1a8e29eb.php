<?php $__env->startSection('content'); ?>
    <div class="error-container">
        <div class="error-content">
            <div class="error-title"><?php echo e($title); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.admin.main', [
    'template' => 'templates.admin.empty',
    'menu' => Page::all()->where('menu', 1),
    'nonmenu' => Page::all()->where('menu', 0)
], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>