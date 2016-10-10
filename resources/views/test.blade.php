<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Test</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/lodash/4.16.4/lodash.min.js"></script>
</head>
<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Add your site or application content here -->
<p>Hello world! This is HTML5 Boilerplate.</p>

<div class="grid-stack">
    <div class="grid-stack-item"
         data-gs-x="0" data-gs-y="0"
         data-gs-width="4" data-gs-height="2">
        <div class="grid-stack-item-content"></div>
    </div>
    <div class="grid-stack-item"
         data-gs-x="4" data-gs-y="0"
         data-gs-width="4" data-gs-height="4">
        <div class="grid-stack-item-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var options = {
            cellHeight: 80,
            verticalMargin: 10
        };
        $('.grid-stack').gridstack(options);
    });
</script>

</body>
</html>