@extends(isset($template) ? $template : 'templates.admin.empty')

@section('head')
    @parent
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title : config('app.name', 'Skytz CMS') }}</title>

    <!-- Styles -->
    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <script>
        function overrideFavicon() {
            $("link[rel*='icon']").remove();
        }
        overrideFavicon();
    </script>
    <link href="/css/images/favicon.png" rel="shortcut icon" />
    <link href="/plugins/ContentTools/build/content-tools.min.css" type="text/css" rel="stylesheet">
    <link href="/plugins/sweetalert2/sweetalert2.css" type="text/css" rel="stylesheet">
    <link href="/plugins/awesomplete/awesomplete.css" type="text/css" rel="stylesheet">
    <link href="/plugins/summernote/summernote.css" rel="stylesheet">

    <!-- Scripts -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/gridstack.js/0.2.6/gridstack.min.css" />
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>

        window.headerWidth = "{{ config('skytz.header_width') }}";
        window.headerHeight = "{{ config('skytz.header_height') }}";
        window.baseUrl = "{{ url("") }}";
        window.currentPage = "{{ isset($currentPage) ? $currentPage->id : 0 }}"
    </script>
@stop

@section('header_cms')
    <div id="cms">
        @include('templates.admin.partials.header')
    </div>
@stop

@section('bottom')
    @if (!Auth::guest())

        {{-- Javascript --}}
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script src="/js/libraries.js"></script>
        <script type="text/javascript" src='/plugins/gridstack/gridstack.js'></script>
        <script src="/js/editor.js"></script>
        <script src="/js/request.js"></script>
        <script src="/plugins/sweetalert2/sweetalert2.js"></script>
        <script src="/plugins/summernote/summernote.min.js"></script>
        <script src="/plugins/summernote/lang/summernote-nl-NL.js"></script>
        <script src="/plugins/nestedSortable/jquery.nestedSortable.js"></script>
        <script src="/plugins/tinymce/tinymce.min.js"></script>
	    <script type="text/javascript" src="/js/plupload.full.min.js"></script>

        {{-- Modals --}}
        @if (isset($currentPage))
            @include('templates.admin.modals.page')
        @endif

        @include('templates.admin.modals.newpage')
        @include('templates.admin.modals.account')
        @include('templates.admin.modals.website', ['settings' => Setting::all()->keyBy('name')])

        <div id="vue-app">
            @include('templates.admin.modals.pages')
            @include('templates.admin.modals.menu')
            @include('templates.admin.modals.media')
            @include('templates.admin.modals.module_forms', ['settings' => Setting::all()->keyBy('name')])
            @include('templates.admin.modals.module_articles')
            @include('templates.admin.modals.module_albums')
            @include('templates.admin.modals.module_sliders')
            @include('templates.admin.modals.module_html_blocks')
            @include('templates.admin.modals.module_social')
            @include('templates.admin.modals.module_breadcrumbs')
            @include('templates.admin.modals.module_albums_overview')
            @include('templates.admin.modals.module_projects')
            @include('templates.admin.modals.headers')
            @include('templates.admin.modals.users')
            @include('templates.admin.modals.select_media')
            @include('templates.admin.modals.select_media_with_edit')
            @include('templates.admin.modals.select_module')
        </div>

        @include('templates.admin.modals.add_media')

        {{-- Flash messages --}}
        <script type="text/javascript">

            @if (Session::has('flash_message'))
            swal({
                title: "{{ Session::has('flash_title') ? session('flash_title') : "Success!" }}",
                    text: "{{ session('flash_message') }}",
                    type: "success",
                    timer: 2000
                }).done();
            @endif

            @if (Session::has('flash_error'))
                swal({
                    title: "{{ Session::has('flash_title') ? session('flash_title') : "Success!" }}",
                    text: "{{ session('flash_error') }}",
                    type: "error"
                }).done();
            @endif

        </script>

        <script src="/js/app.js"></script>
    @endif
@stop
