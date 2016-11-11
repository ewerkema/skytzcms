<div class="modal <?php echo e(isset($animation) ? $animation : 'fade'); ?>" data-backdrop="<?php echo e(isset($backdrop) ? $backdrop : ''); ?>" tabindex="-1" role="dialog" id="<?php echo e($target); ?>" aria-labelledby="<?php echo e($target); ?>">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $__env->yieldContent('modal-header'); ?>
            </div>
            <div class="modal-body">
                <?php echo $__env->yieldContent('modal-body'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <?php echo $__env->yieldContent('modal-footer'); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->yieldContent('javascript'); ?>