
@extends($template)

@section('head')
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Skytz CMS') }}</title>

    <!-- Styles -->
    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />
    <link href="/plugins/contenttools/content-tools.min.css" type="text/css" rel="stylesheet">
    <link href="/plugins/sweetalert2/sweetalert2.css" type="text/css" rel="stylesheet">

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
    <script src="/plugins/contenttools/content-tools.min.js"></script>
    <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/js/editor.js"></script>

    {{-- Flash messages --}}
    <script type="text/javascript">
        @if (Session::has('flash_message'))
            swal({
                title: "{{ Session::has('flash_title') ? session('flash_title') : "Success!" }}",
                text: "{{ session('flash_message') }}",
                type: "success",
                timer: 2000
            });
        @endif

        @if (Session::has('flash_error'))
            swal({
                title: "{{ Session::has('flash_title') ? session('flash_title') : "Success!" }}",
                text: "{{ session('flash_error') }}",
                type: "error"
            });
        @endif
    </script>

    {{-- Modals --}}
    @if (!Auth::guest())
        @include('templates.admin.modals.page')
    @endif
@stop