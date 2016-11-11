<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Module contact:</strong> formulieren aanmaken</h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <list-forms></list-forms>
    <div class="clear"></div>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'moduleFormsModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>