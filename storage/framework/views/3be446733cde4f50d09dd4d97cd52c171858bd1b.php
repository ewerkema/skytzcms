<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Website instellingen</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#mainTab" aria-controls="mainTab" role="tab" data-toggle="tab">Algemeen</a></li>
        <li role="presentation"><a href="#analyticsTab" aria-controls="analyticsTab" role="tab" data-toggle="tab">Google Analytics</a></li>
        <li role="presentation"><a href="#socialTab" aria-controls="socialTab" role="tab" data-toggle="tab">Social Media</a></li>
        <li role="presentation"><a href="#otherTab" aria-controls="otherTab" role="tab" data-toggle="tab">Overig</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="alert form-message" role="alert" style="display: none;"></div>
    <form action="#" class="form-horizontal" id="websiteForm">
        <div class="tab-content" id="websiteTabs">
            <div role="tabpanel" class="tab-pane active" id="mainTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Website naam</label>

                    <div class="col-md-8">
                        <input type="text" name="meta_title" value="<?php echo e($settings['meta_title']->value); ?>" class="form-control" placeholder="Website naam" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Website beschrijving</label>

                    <div class="col-md-8">
                        <textarea type="text" name="meta_descr" class="form-control" placeholder="Webiste beschrijving" autofocus><?php echo e($settings['meta_descr']->value); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Footer tekst</label>

                    <div class="col-md-8">
                        <textarea type="text" name="footerblock" class="form-control" placeholder="Footer tekst" autofocus><?php echo e($settings['footerblock']->value); ?></textarea>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="analyticsTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Google Analytics tracking ID:</label>

                    <div class="col-md-8">
                        <input type="text" name="googleanalytics" value="<?php echo e($settings['googleanalytics']->value); ?>" class="form-control" placeholder="Tracking ID" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Tracking activeren:</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <?php echo Form::checkbox('recordgoogle', 'recordgoogle', $settings['recordgoogle']->value); ?>

                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="socialTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Facebook link</label>

                    <div class="col-md-8">
                        <input type="text" name="facebook_page" value="<?php echo e($settings['facebook_page']->value); ?>" class="form-control" placeholder="Facebook link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Twitter link</label>

                    <div class="col-md-8">
                        <input type="text" name="twitter_page" value="<?php echo e($settings['twitter_page']->value); ?>" class="form-control" placeholder="Twitter link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Youtube link</label>

                    <div class="col-md-8">
                        <input type="text" name="youtube_page" value="<?php echo e($settings['youtube_page']->value); ?>" class="form-control" placeholder="Youtube link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Google Plus link</label>

                    <div class="col-md-8">
                        <input type="text" name="googleplus_page" value="<?php echo e($settings['googleplus_page']->value); ?>" class="form-control" placeholder="Google Plus link" autofocus />
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="otherTab">

            </div>
        </div>
    </form>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" form="websiteForm" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        $('#websiteTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        var request = new Request('<?php echo e(cms_url('settings')); ?>');
        request.setType('PATCH');
        request.setForm('#websiteForm');

        request.addFields(['meta_title', 'meta_descr', 'footerblock', 'googleanalytics', 'googleanalytics', 'facebook_page', 'twitter_page', 'youtube_page', 'googleplus_page']);
        request.addField('recordgoogle', 'checkbox', false);

        request.onSubmit(function(data) {
            $('#websiteModal').modal('toggle');
            swal({
                title: 'Success!',
                text: 'Website instellingen zijn succesvol aangepast.',
                type: "success",
                timer: 2000
            });
        });
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'websiteModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>