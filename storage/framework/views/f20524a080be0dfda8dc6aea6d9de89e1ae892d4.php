<div class="news-wrapper">
    <div id="news-<?php echo e($id); ?>" class="portlet">
        <h1>Album: <?php echo e(Album::find($id)->name); ?></h1>
        <?php $__currentLoopData = Album::find($id)->media()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <a href="<?php echo e($image->photo_url('original')); ?>" target="_blank" class="group1">
                <img src="<?php echo e($image->photo_url('thumbnail')); ?>" alt="<?php echo e($image->description); ?>">
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </div>
</div>