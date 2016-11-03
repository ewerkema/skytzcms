<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Pagina instellingen:</strong> <?php echo e($currentPage->meta_title); ?></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <?php echo Form::open(array('id' => 'pageForm', 'class' => 'form-horizontal')); ?>


    <div class="alert form-message" role="alert" style="display: none;"></div>
    <div class="form-group">
        <?php echo Form::label('title', 'Pagina naam', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::text('title',$currentPage->title, array('class'=>'form-control','placeholder' => 'Pagina naam', 'required', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('slug', 'Pagina link', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon" id="page-url"><?php echo e(url("/ ")); ?></span>
                <?php echo Form::text('slug', ($currentPage->slug=="index") ? "" : $currentPage->slug, array('class'=>'form-control', 'autofocus', 'aria-describedby' => 'page-url')); ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::text('meta_title', $currentPage->meta_title,array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <?php echo Form::textarea('meta_desc',$currentPage->meta_desc,array('class'=>'form-control','placeholder' => 'Pagina beschrijving', 'autofocus')); ?>

        </div>
    </div>
    <div class="form-group">
        <?php echo Form::label('menu', 'Weergeven in menu', ['class' => 'col-md-3 control-label']); ?>


        <div class="col-md-8">
            <label class="Switch">
                <?php echo Form::checkbox('menu', 'menu', $currentPage->menu); ?>

                <div class="Switch__slider"></div>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="parent_id" class="col-md-3 control-label">Weergeven in submenu van</label>

        <div class="col-md-8">
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="" <?php echo e((!$currentPage->parent_id) ? "selected" : ""); ?>>Geen submenu</option>
                <?php $__currentLoopData = Page::getMenuWithoutSubpages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                    <option value="<?php echo e($page->id); ?>" <?php echo e(($page->id == $currentPage->parent_id) ? "selected" : ""); ?>>
                        <?php echo e($page->title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </select>
        </div>
    </div>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        var request = new Request('<?php echo e(cms_url('pages/'.$currentPage->id)); ?>');
        request.setType('PATCH');
        request.setForm('#pageForm');

        request.addFields(['title', 'meta_title', 'meta_desc', 'parent_id']);
        request.addCheckboxes(['menu']);
        request.addField('slug', 'text', 'index');

        request.onSubmit(function(data) {
            if (data['redirectTo'] === undefined) {
                request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                return;
            }

            window.location.href = '<?php echo e(cms_url("/")); ?>/'+data['redirectTo'];
        });

        var subpageSelect = $('[name=parent_id]');
        var visibleInMenu = $('[name=menu]');
        subpageSelect.change(function() {
            var value = $(this).val();

            if (value)
                visibleInMenu.prop('checked', true);
        });

        visibleInMenu.change(function() {
            var checked = $(this).is(":checked");

            if (!checked)
                subpageSelect.val(subpageSelect.find('option:first').val());
        })
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'pageModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>