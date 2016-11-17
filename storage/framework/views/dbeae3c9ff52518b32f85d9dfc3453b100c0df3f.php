<div class="slider-wrapper" style="height: 300px;">
    <div id="slider-<?php echo e($id); ?>" style="height:300px;">
        <?php $__currentLoopData = Slider::find($id)->media()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <div class="slide<?php echo e($image->id); ?>"><img src="<?php echo e($image->photo_url('original')); ?>" alt="<?php echo e($image->description); ?>" /></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </div>
    <div id="slider-direction-nav"></div>
    <div id="slider-control-nav"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var slider = $('#slider-<?php echo e($id); ?>').leanSlider({
            directionNav: '#slider-direction-nav',
            controlNav: '#slider-control-nav'
        });
    });
</script>