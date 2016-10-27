
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
    <link href="/plugins/awesomplete/awesomplete.css" type="text/css" rel="stylesheet">

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
    @if (!Auth::guest())
        <script src="/js/app.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script type="text/javascript" src='/plugins/gridstack/gridstack.js'></script>

        <script src="/js/editor.js"></script>
        <script src="/js/request.js"></script>
        <script src="/plugins/sweetalert2/sweetalert2.js"></script>
        
        {{-- new added --}}
        <script type="text/javascript" src="/js/plupload.full.min.js"></script>

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
        @include('templates.admin.modals.media')
        @include('templates.admin.modals.add_media')
        @include('templates.admin.modals.newpage')
        @include('templates.admin.modals.account')
        @include('templates.admin.modals.website', ['settings' => Setting::all()->keyBy('name')])

        @if (isset($page))
            @include('templates.admin.modals.page')
        @endif
    @endif
@stop