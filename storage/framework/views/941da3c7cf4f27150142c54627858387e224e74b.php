
<div class="page-content" data-page="<?php echo e($currentPage->id); ?>">
    <?php if(sizeof($currentPage->content) == 0): ?>
        <div class="block" data-name="block0" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>
    <?php endif; ?>

    <?php $__currentLoopData = $currentPage->getContent(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <div class="row">
            <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <div class="block columns medium-<?php echo e($block['width']); ?> medium-offset-<?php echo e($block['offset']); ?>" data-name="<?php echo e($block['name']); ?>" data-gs-x="<?php echo e($block['x']); ?>" data-gs-y="<?php echo e($block['y']); ?>" data-gs-width="<?php echo e($block['width']); ?>" data-gs-height="<?php echo e($block['height']); ?>" data-module="<?php echo e(isset($block['module']) ? $block['module'] : 0); ?>" data-module-id="<?php echo e(isset($block['module_id']) ? $block['module_id'] : 0); ?>" data-editable>
                    <?php echo $block['content']; ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</div>

<?php if(!Auth::guest()): ?>
    <script type="text/javascript">

        function publishWebsite() {

            swal({
                title: "Website publiceren?",
                text: "Alle gemaakte wijzigingen zullen zichtbaar worden op de website.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2ab27b",
                confirmButtonText: "Ja, website publiceren",
            }).then(function(){
                var request = new Request('<?php echo e(cms_url('pages/publish')); ?>');
                request.setType('POST');
                request.send(function() {
                    swal({
                        title: 'Website gepubliceerd!',
                        text: 'Alle wijzigingen zijn succesvol online gezet.',
                        type: 'success',
                        timer: 2000
                    });
                });
            });
        }

        function reloadPageContent() {
            var blockContent = $('.page-content');
            blockContent.html("");

            $.get('/cms/pages/<?php echo e($currentPage->id); ?>/content', function(content) {
                if (content.length == 0) {
                    blockContent.html('<div class="block" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>');
                }

                _.each(content, function(row) {
                    var elements = $('<div class="row"></div>');
                    _.each(row, function (item) {
                        var newRow = (item['first']) ? "clear" : "";
                        var module = (item['module'] === undefined) ? 0 : item['module'];
                        var module_id = (item['module_id'] === undefined) ? 0 : item['module_id'];
                        var element = '<div class="block columns medium-'+item['width']+' medium-offset-'+item['offset']+' '+newRow+'" data-name="'+item['name']+'" data-gs-x="'+item['x']+'" data-gs-y="'+item['y']+'" data-gs-width="'+item['width']+'" data-gs-height="'+item['height']+'" data-module="'+module+'" data-module-id="'+module_id+'" data-editable>'+item['content']+'</div>';

                        elements.append(element);
                    });
                    blockContent.append(elements);
                });
            });
        }

        function addPagesToEditor (ContentTools)  {
            var _linkDialogMount = ContentTools.LinkDialog.prototype.mount;
            ContentTools.LinkDialog.prototype.mount = function() {
                // Call original behaviour
                _linkDialogMount.apply(this);

                // Add the auto-complete to the link input (we provide a static list but most likely you'd
                // have the auto-complete call the server for a list).
                new Awesomplete(this._domInput, {
                    list: <?php echo Page::getEditorLinks(); ?>

                });
            };
        }


    </script>
<?php endif; ?>