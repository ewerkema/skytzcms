<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo e($page->meta_title); ?></title>

    <link rel="stylesheet" href="<?php echo e(template_url('/css/foundation.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(template_url('/css/style.css')); ?>" />

    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

    <!-- Main jQuery -->
    <script src="<?php echo e(template_url('/css/main.js')); ?>"></script>

    <!-- Larevel -->
    <?php echo $__env->yieldContent('head'); ?>

    <!-- END BLOCK : HeaderContents -->
    <!-- START BLOCK : AdminContents -->
    
    <!-- START BLOCK : LoginScreen -->
    
        
            
            
            
            
            
            
                
            
            
        
    
    
    <!-- END BLOCK : LoginScreen -->
    
    
    <!-- END BLOCK : AdminContents -->
    <!-- START BLOCK : GoogleAnalytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '{tracking-id}', '{server}');
        ga('send', 'pageview');

    </script>
    <!-- END BLOCK : GoogleAnalytics -->
</head>
<body>


<?php echo $__env->yieldContent('header_cms'); ?>


<!-- SKYTZ CMS -->
<!-- END BLOCK : ShowAdminOptions -->
<div class="row">

    <!-- START CONTAINER -->
    <div class="container">


        <!-- START HEADER -->
        <div class="header">


            <div class="row">


                <div class="small-12 medium-4 columns logo">

                    <a href="<?php echo e(page_url("/")); ?>"><img src="<?php echo e(template_url('/img/logo.png')); ?>" alt="CMS demo"></a>

                </div>


                <div id="hamburger">

                    <img src="<?php echo e(template_url('/img/hamburger.png')); ?>" alt="Menu" />

                    <h2>Menu</h2>

                </div>



                <div class="small-12 medium-12 large-8 columns menu">

                    <ul>
                        <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menupage): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <li class="<?php echo e((isset($page) && $menupage->id == $page->id) ? "active" : ""); ?>">
                                <a href="<?php echo e(page_url($menupage->slug)); ?>"><?php echo e($menupage->title); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </ul>

                </div>


            </div>


        </div>
        <!-- END HEADER -->


        <!-- START CONTENT -->
        <div class="content">

            <div class="row">

                <!-- START LEFT SIDE -->
                <div class="small-12 medium-12 large-12 columns left">

                    <div class="row">

                        <!-- START TEKST -->
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
                        <!-- END TEKST -->

                    </div>

                </div>
                <!-- END LEFT SIDE -->

            </div>


        </div>
        <!-- END CONTENT -->



    </div>
    <!-- END CONTAINER -->

</div>
<div class="bottom">
    <div class="row ">
        <div class="small-12 columns sitemap">
            <p>Copyright 2016 - <?php echo e(date('Y')); ?> <a href="/">Skytz.nl</a>  |  Uw eigen website gemakkelijk beheren</p>
        </div>
    </div>
</div>

<script src="<?php echo e(template_url('/js/foundation.min.js')); ?>"></script>
<script>
    $(document).foundation();
</script>

<?php echo $__env->yieldContent('bottom'); ?>

</body>
</html>