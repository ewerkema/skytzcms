<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" value="<?php echo e($currentPage->meta_desc); ?>">

    <title><?php echo e($currentPage->meta_title); ?></title>

    <link rel="stylesheet" href="<?php echo e(template_url('/css/foundation.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(template_url('/css/style.css')); ?>" />

    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

    <!-- Main jQuery -->
    <script src="<?php echo e(template_url('/css/main.js')); ?>"></script>

    <!-- Larevel -->
    <?php echo $__env->yieldContent('head'); ?>

    <!-- Custom packages -->
    <script src="<?php echo e(template_url('/js/lean-slider/lean-slider.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(template_url('/css/lean-slider/lean-slider.css')); ?>" type="text/css" />

    <!-- END BLOCK : HeaderContents -->
    <!-- START BLOCK : AdminContents -->
    
    <!-- START BLOCK : LoginScreen -->
    
        
            
            
            
            
            
            
                
            
            
        
    
    
    <!-- END BLOCK : LoginScreen -->
    
    
    <!-- END BLOCK : AdminContents -->

    <?php echo $__env->make('templates.admin.partials.analytics', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</head>
<body>


<?php echo $__env->yieldContent('header_cms'); ?>

<div class="row">
    <div class="container">
        <div class="header">
            <div class="row">

                <div class="small-12 medium-4 columns logo">
                    <a href="<?php echo e(page_url("/")); ?>"><img src="<?php echo e(template_url('/img/logo.png')); ?>" alt="CMS demo"></a>
                </div>

                <div id="hamburger">
                    <img src="<?php echo e(template_url('/img/hamburger.png')); ?>" alt="Menu" />
                    <h2>Menu</h2>
                </div>

                <?php echo $__env->make('templates.admin.partials.menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="small-12 medium-12 large-12 columns left">
                    <div class="row">
                        <div class="tekst">

                            <!-- START BLOCK : Slider -->
                            <div class="slider-wrapper" style="height: {height_wrapper}px; display: {display};">
                                <div id="slider" style="height:{height_slider}px;">
                                    
                                </div>
                                <div id="slider-direction-nav"></div>
                                <div id="slider-control-nav"></div>
                            </div>

                            
                                
                                    
                                        
                                        
                                    
                                
                            
                            <div class="image header-image"><img src="<?php echo e(template_url('/img/header.png')); ?>" alt=""></div>

                            <?php echo $__env->make('templates.admin.partials.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                            <!-- END BLOCK : Slider -->

                            <!-- START BLOCK : NewsItems -->
                            
                            
                            <!-- END BLOCK : NewsItems -->


                            <!-- START BLOCK : StrokeList -->
                            
                                
                                
                                
                                
                                
                                    
                                
                                
                            
                            <!-- END BLOCK : StrokeList -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="bottom">
    <div class="row ">
        <div class="small-12 columns sitemap">
            <?php if(!empty(Setting::get('footerblock'))): ?>
                <p><?php echo e(Setting::get('footerblock')); ?></p>
            <?php else: ?>
                <p>Copyright 2016 - <?php echo e(date('Y')); ?> <a href="/">Skytz.nl</a>  |  Uw eigen website gemakkelijk beheren</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php echo $__env->yieldContent('bottom'); ?>

<script src="<?php echo e(template_url('/js/foundation.min.js')); ?>"></script>
<script>
    $(document).foundation();
</script>
</body>
</html>