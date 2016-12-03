<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" value="{{ $currentPage->meta_desc }}">
        <link href="{{ template_url('/img/favicon.png') }}" rel="shortcut icon" />

        <title>{{ $currentPage->meta_title }}</title>

        <link rel="stylesheet" href="{{ template_url('/css/foundation.css') }}" />
        <link rel="stylesheet" href="{{ template_url('/css/style.css') }}" />

        <!-- jQuery -->
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

        <!-- Main jQuery -->
        <script src="{{ template_url('/css/main.js') }}"></script>

        <!-- Larevel -->
        @yield('head')

        @include('templates.admin.partials.analytics')
    </head>
    <body>

        {{-- Skytz CMS Header --}}
        @yield('header_cms')

        <div class="row">
            <div class="container">
                <div class="header row">
                    <div class="small-12 medium-4 columns logo">
                        <a href="{{ page_url("/") }}"><img src="{{ template_url('/img/logo.png') }}" alt="CMS demo"></a>
                    </div>

                    <div id="hamburger">
                        <img src="{{ template_url('/img/hamburger.png') }}" alt="Menu" />
                        <h2>Menu</h2>
                    </div>

                    @include('templates.admin.partials.menu')
                </div>
                <div class="content row">
                    <div class="small-12 medium-12 large-12 columns left tekst">
                        @include('templates.admin.partials.page_header')

                        @include('templates.admin.partials.content')
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom">
            <div class="row ">
                <div class="small-12 columns sitemap">
                    @if (!empty(Setting::get('footerblock')))
                        <p>{{ Setting::get('footerblock') }}</p>
                    @else
                        <p>Copyright 2016 - {{ date('Y') }} <a href="/">Skytz.nl</a>  |  Uw eigen website gemakkelijk beheren</p>
                    @endif
                </div>
            </div>
        </div>

        @yield('bottom')

        <script src="{{ template_url('/js/foundation.min.js') }}"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>