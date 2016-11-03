<?php $__env->startSection('head'); ?>
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(isset($title) ? $title : config('app.name', 'Skytz CMS')); ?></title>

    <!-- Styles -->
    <link href="<?php echo e(elixir('/css/app.css')); ?>" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />
    <link href="/plugins/contenttools/content-tools.min.css" type="text/css" rel="stylesheet">
    <link href="/plugins/sweetalert2/sweetalert2.css" type="text/css" rel="stylesheet">
    <link href="/plugins/awesomplete/awesomplete.css" type="text/css" rel="stylesheet">

    <!-- Scripts -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css" />
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_cms'); ?>
    <div id="cms">
        <?php echo $__env->make('templates.admin.partials.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('bottom'); ?>
    <?php if(!Auth::guest()): ?>
        <script src="/js/app.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script type="text/javascript" src='/plugins/gridstack/gridstack.js'></script>

        <script src="/js/editor.js"></script>
        <script src="/js/request.js"></script>
        <script src="/plugins/sweetalert2/sweetalert2.js"></script>
        
        
        <script type="text/javascript" src="/js/plupload.full.min.js"></script>

        
        <script type="text/javascript">

            <?php if(Session::has('flash_message')): ?>
                swal({
                    title: "<?php echo e(Session::has('flash_title') ? session('flash_title') : "Success!"); ?>",
                    text: "<?php echo e(session('flash_message')); ?>",
                    type: "success",
                    timer: 2000
                });
            <?php endif; ?>

            <?php if(Session::has('flash_error')): ?>
                swal({
                    title: "<?php echo e(Session::has('flash_title') ? session('flash_title') : "Success!"); ?>",
                    text: "<?php echo e(session('flash_error')); ?>",
                    type: "error"
                });
            <?php endif; ?>
        </script>

        
        <?php echo $__env->make('templates.admin.modals.media', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('templates.admin.modals.add_media', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('templates.admin.modals.newpage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('templates.admin.modals.account', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('templates.admin.modals.website', ['settings' => Setting::all()->keyBy('name')], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <?php if(isset($page)): ?>
            <?php echo $__env->make('templates.admin.modals.page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make(isset($template) ? $template : 'templates.admin.empty', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>