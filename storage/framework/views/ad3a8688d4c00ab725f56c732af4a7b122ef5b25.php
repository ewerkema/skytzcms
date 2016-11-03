<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <!-- Larevel -->
    <?php echo $__env->yieldContent('head'); ?>

</head>
<body>

<?php if(is_cms()): ?>
    <?php echo $__env->yieldContent('header_cms'); ?>
<?php endif; ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->yieldContent('bottom'); ?>

</body>
</html>