<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Pagina instellingen:</strong> <?php echo e($page->meta_title); ?></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <?php echo Form::open(array('id' => 'pageForm', 'class' => 'form-horizontal')); ?>


    <div class="alert form-message" role="alert" style="display: none;"></div>
    <div class="form-group">
        <?php echo Form::label('title', 'Pagina naam', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::text('title',$page->title, array('class'=>'form-control','placeholder' => 'Pagina naam', 'required', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('slug', 'Pagina link', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon" id="page-url"><?php echo e(url("/ ")); ?></span>
                <?php echo Form::text('slug', ($page->slug=="index") ? "" : $page->slug, array('class'=>'form-control', 'autofocus', 'aria-describedby' => 'page-url')); ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::text('meta_title', $page->meta_title,array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::textarea('meta_desc',$page->meta_desc,array('class'=>'form-control','placeholder' => 'Pagina beschrijving', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('menu', 'Weergeven in menu', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <label class="Switch">
                <?php echo Form::checkbox('menu', 'menu', $page->menu); ?>

                <div class="Switch__slider"></div>
            </label>
        </div>
    </div>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        var request = new Request('<?php echo e(cms_url('pages/'.$page->id)); ?>');
        request.setType('PATCH');
        request.setForm('#pageForm');

        request.addField('title');
        request.addField('slug', 'text', 'index');
        request.addField('meta_title');
        request.addField('meta_desc');
        request.addField('menu', 'checkbox');

        request.onSubmit(function(data) {
            if (data['slug'] === undefined) {
                this.form.find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
            }

            window.location.href = '<?php echo e(cms_url("/")); ?>/'+data['slug'];
        });
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'pageModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>