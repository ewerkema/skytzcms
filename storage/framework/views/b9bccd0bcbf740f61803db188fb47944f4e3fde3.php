<div class="page-content" data-page="<?php echo e($currentPage->id); ?>">

    <?php $__currentLoopData = $currentPage->getPublishedContent(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <div class="row">
            <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <?php if($block['module']): ?>
                    <div class="block columns medium-<?php echo e($block['width']); ?> medium-offset-<?php echo e($block['offset']); ?>">
                        <?php echo $__env->make('templates.admin.modules.'.Module::find($block['module'])->template, ['id' => $block['module_id']], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                <?php else: ?>
                    <div class="block columns medium-<?php echo e($block['width']); ?> medium-offset-<?php echo e($block['offset']); ?>">
                        <?php echo $block['content']; ?>

                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</div>