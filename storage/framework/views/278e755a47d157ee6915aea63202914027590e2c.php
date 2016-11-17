<?php if($currentPage->header != NULL): ?>
    <div class="image header-image"><img src="/<?php echo e($currentPage->header->path); ?>" alt=""></div>
<?php elseif(Setting::get('header_image')): ?>
    <div class="image header-image"><img src="/<?php echo e(Media::find(Setting::get('header_image'))->path); ?>" alt=""></div>
<?php elseif(Setting::get('header_slider')): ?>
    <?php echo $__env->make('templates.admin.modules.sliders', ['id' => Setting::get('header_slider')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>