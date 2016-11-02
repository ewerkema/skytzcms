<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $currentPage->meta_title }}</title>

    <link rel="stylesheet" href="{{ template_url('/css/foundation.css') }}" />
    <link rel="stylesheet" href="{{ template_url('/css/style.css') }}" />

    <!-- jQuery -->
    <script src="http://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

    <!-- Main jQuery -->
    <script src="{{ template_url('/css/main.js') }}"></script>

    <!-- Larevel -->
    @yield('head')

    <!-- END BLOCK : HeaderContents -->
    <!-- START BLOCK : AdminContents -->
    {{--{IncludeFiles}--}}
    <!-- START BLOCK : LoginScreen -->
    {{--<script language="javascript">--}}
        {{--$(document).ready(function(){--}}
            {{--$.colorbox({inline:true, href:"#admin_login", innerWidth:390, innerHeight:350, overlayClose: false, escKey: false, closeButton: false, open: true, opacity: "0.85"});--}}
            {{--$('#cboxClose').remove();--}}
            {{--$(".SkytzCreateSpace").remove();--}}
            {{--$(".SkytzCms").remove();--}}
            {{--$('#cboxOverlay').css('background-color', '#58ACE0');--}}
            {{--$('body').bind('contextmenu', function(e) {--}}
                {{--return false;--}}
            {{--});--}}
            {{--$('#SkytzConnectTo').val('{sitename}');--}}
        {{--});--}}
    {{--</script>--}}
    {{--<script type="text/javascript" src="/controller/jquery/jqueryLogin.js"></script>--}}
    <!-- END BLOCK : LoginScreen -->
    {{--{DragDropBlockFiles}--}}
    {{--{CkEditor}--}}
    <!-- END BLOCK : AdminContents -->
    @include('templates.admin.partials.analytics')
</head>
<body>

{{-- Skytz CMS Header --}}
@yield('header_cms')

<div class="row">
    <div class="container">
        <div class="header">
            <div class="row">

                <div class="small-12 medium-4 columns logo">
                    <a href="{{ page_url("/") }}"><img src="{{ template_url('/img/logo.png') }}" alt="CMS demo"></a>
                </div>

                <div id="hamburger">
                    <img src="{{ template_url('/img/hamburger.png') }}" alt="Menu" />
                    <h2>Menu</h2>
                </div>

                <nav class="small-12 medium-12 large-8 columns menu">
                    <ul>
                        @foreach (Page::getMenuWithSubpages() as $page)
                            <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }} {{ (isset($page->subpages)) ? "has-dropdown" : "" }}">

                                <a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a>

                                @if (isset($page->subpages))
                                    <ul class="dropdown">
                                        @foreach ($page->subpages as $subpage)
                                            <li class="{{ (isset($currentPage) && $subpage->id == $currentPage->id) ? "active" : "" }}">
                                                <a href="{{ page_url($subpage->getSlug()) }}">{{ $subpage->title }}</a>
                                            </li>
                                        @endforeach
                                     </ul>
                                @endif
                             </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="small-12 medium-12 large-12 columns left">
                    <div class="row">
                        <div class="tekst">

                            <!-- START BLOCK : Slider -->
                            <div class="slider-wrapper" style="height: {height_wrapper}px; display: {display};">
                                <div id="slider" style="height:{height_slider}px;">
                                    {{--{slider}--}}
                                </div>
                                <div id="slider-direction-nav"></div>
                                <div id="slider-control-nav"></div>
                            </div>

                            {{--<script type="text/javascript">--}}
                                {{--$(document).ready(function() {--}}
                                    {{--var slider = $('#slider').leanSlider({--}}
                                        {{--directionNav: '#slider-direction-nav',--}}
                                        {{--controlNav: '#slider-control-nav'--}}
                                    {{--});--}}
                                {{--});--}}
                            {{--</script>--}}
                            <div class="image header-image"><img src="{{ template_url('/img/header.png') }}" alt=""></div>

                            @include('templates.admin.partials.content')

                            <!-- END BLOCK : Slider -->

                            <!-- START BLOCK : NewsItems -->
                            {{--<strong>{title}</strong> <br /> <br />--}}
                            {{--<p>{item}</p>--}}
                            <!-- END BLOCK : NewsItems -->


                            <!-- START BLOCK : StrokeList -->
                            {{--<div class="columns {width}" id="stroke_{id}">--}}
                                {{--<!-- START BLOCK : Blocks -->--}}
                                {{--<!-- START BLOCK : Options -->--}}
                                {{--<div class="options" linked="arrayorder_{id}"><a href="/controller/blocks/AddModule/?Bid={id}" class="AddModule"><img src="/controller/images/module-toevoegen.png" style="float: left;" class="vtip" title="Module toevoegen aan tekstvlak"></a> <a href="/controller/blocks/visibleBlock/?Bid={id}" class="visibleBlock"><img src="/controller/images/{visible_icon}.png" class="vtip" title="Zichtbaarheid bewerken"></a> <!--<a href="/controller/blocks/stuckBlock/?Bid={id}" class="stuckBlock"><img src="/controller/images/{stuck_icon}.png" class="vtip" title="Tekstvlak vast zetten"></a>--> <a href="/controller/blocks/deleteBlock/?Bid={id}" class="DeleteBlock"><img src="/controller/images/verwijderen.png" class="vtip" title="Tekstvlak verwijderen"></a> <a href="/controller/blocks/editBlock/?Bid={id}" class="EditBlock"><img src="/controller/images/bewerken.png" class="vtip" title="Tekstvlak bewerken"></a></div>--}}
                                {{--<!-- END BLOCK : Options -->--}}
                                {{--<div class="portlet" id="arrayorder_{id}"{BlockAdminOptions}>--}}
                                    {{--{content}--}}
                                {{--</div>--}}
                                {{--<!-- END BLOCK : Blocks -->--}}
                            {{--</div>--}}
                            <!-- END BLOCK : StrokeList -->
                        </div>
                    </div>
                </div>
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