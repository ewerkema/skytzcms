<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Module albums</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <list-albums></list-albums>
    <div class="clear"></div>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'moduleAlbumsModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>