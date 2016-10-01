
@extends($template)

@section('head')
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Skytz CMS') }}</title>

    <!-- Styles -->
    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />
    <link href="/contenttools/content-tools.min.css" type="text/css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
@stop

@section('header_cms')
    <div id="cms">
        @include('templates.admin.partials.header')
    </div>
@stop

@section('bottom')
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script src="/contenttools/content-tools.min.js"></script>
    <script src="/js/editor.js"></script>

    {{-- Modals --}}
    @if (isset($page))
        @include('templates.admin.modals.page')
    @endif
@stop