<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Nieuwe pagina aanmaken</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <form action="#" class="form-horizontal" id="newPageForm">
        <div class="alert form-message" role="alert" style="display: none;"></div>
        <div class="form-group">
            <label for="title" class="col-md-3 control-label">Pagina naam</label>

            <div class="col-md-8">
                <input type="text" name="title" class="form-control" placeholder="Pagina naam" required autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="slug" class="col-md-3 control-label">Pagina link</label>

            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon" id="page-url"><?php echo e(url("/ ")); ?></span>
                    <input type="text" name="slug" class="form-control" aria-describedby="page-url" required autofocus />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="meta_title" class="col-md-3 control-label">Pagina titel</label>

            <div class="col-md-8">
                <input type="text" name="meta_title" class="form-control" placeholder="Pagina titel" required autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="meta_desc" class="col-md-3 control-label">Pagina beschrijving</label>

            <div class="col-md-8">
                <textarea name="meta_desc" class="form-control" placeholder="Pagina beschrijving" autofocus></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="menu" class="col-md-3 control-label">Weergeven in menu</label>

            <div class="col-md-8">
                <label class="Switch">
                    <input type="checkbox" name="menu">
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>

    </form>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" form="newPageForm" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        var request = new Request('<?php echo e(cms_url('pages')); ?>');
        request.setType('POST');
        request.setForm('#newPageForm');

        request.addFields(['title', 'meta_title', 'meta_desc']);
        request.addField('slug', 'text', 'index');
        request.addField('menu', 'checkbox');

        request.onSubmit(function(data) {
            if (data['slug'] === undefined) {
                this.form.find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
            }

            window.location.href = '<?php echo e(cms_url("/")); ?>/'+data['slug'];
        });
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'newPageModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>