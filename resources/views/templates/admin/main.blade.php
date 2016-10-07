
@extends(isset($template) ? $template : 'templates.admin.empty')

@section('head')
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : config('app.name', 'Skytz CMS') }}</title>

    <!-- Styles -->
    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />
    <link href="/plugins/contenttools/content-tools.min.css" type="text/css" rel="stylesheet">
    <link href="/plugins/sweetalert2/sweetalert2.css" type="text/css" rel="stylesheet">

    <!-- Scripts -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css" />
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
    <script src="/js/app.js"></script>
    <script src="/plugins/sweetalert2/sweetalert2.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script type="text/javascript" src='/plugins/gridstack/gridstack.min.js'></script>
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
        @if (isset($page))
            @include('templates.admin.modals.page')
        @endif
    @endif
@stop