<?php $__env->startSection('modal-header'); ?>
    <h4 class="modal-title"><strong>Menu indelen</strong></h4>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <p></p>
    <?php if(Page::getMenuWithSubpages()->count()): ?>
        <ul class="sortable">
            <?php $__currentLoopData = Page::getMenuWithSubpages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <li class="menu-item" id="page_<?php echo e($page->id); ?>" data-id="<?php echo e($page->id); ?>">
                    <div>
                        <span class="glyphicon glyphicon-move"></span> <?php echo e($page->title); ?>

                    </div>
                    <?php if(isset($page->subpages)): ?>
                        <ul>
                            <?php $__currentLoopData = $page->subpages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subpage): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <li class="menu-item" id="page_<?php echo e($subpage->id); ?>" data-id="<?php echo e($subpage->id); ?>">
                                    <div>
                                        <span class="glyphicon glyphicon-move"></span> <?php echo e($subpage->title); ?>

                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </ul>
    <?php else: ?>
        <p>Er staan geen pagina's in het menu.</p>
    <?php endif; ?>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="submit" onclick="saveLayoutMenu()" class="btn btn-primary">Opslaan</button>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        $('.sortable').nestedSortable({
            listType: 'ul',
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 2,
            placeholder: 'placeholder',
            forcePlaceholderSize: true
        });

        function saveLayoutMenu() {
            var array = $('.sortable').nestedSortable('toArray');
            $.ajax({
                url: '/cms/pages/order',
                type: 'PATCH',
                data: {
                    pages: array
                },
                success: function() {
                    location.reload();
                }
            });
        }
    </script>
<?php $__env->stopSection(true); ?>
<?php echo $__env->make('templates.admin.modals.modal', ['target'=>'sortMenuModal'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>