<div class="page-content" data-page="<?php echo e($page->id); ?>">

    <?php $__currentLoopData = $page->getPublishedContent(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <div class="row">
            <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="block columns medium-<?php echo e($block['width']); ?> medium-offset-<?php echo e($block['offset']); ?>">
                    <?php echo $block['content']; ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</div>