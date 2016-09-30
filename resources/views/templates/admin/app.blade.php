
@extends($template)

@section('head')
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Skytz CMS') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
@stop

@section('header_cms')
    <div id="cms">
        @include('templates.admin.header')
    </div>
@stop

@section('bottom')
    <!-- Scripts -->
    <script src="/js/app.js"></script>
@stop