<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Selecteer afbeelding</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <select-media></select-media>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">

    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'selectMediaModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>