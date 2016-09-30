<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $page->meta_title }}</title>

    <link rel="stylesheet" href="{{ template_url('/css/foundation.css') }}" />
    <link rel="stylesheet" href="{{ template_url('/css/style.css') }}" />
    <script src="{{ template_url('/js/vendor/modernizr.css') }}"></script>

    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

    <!-- Main jQuery -->
    <script src="{{ template_url('/css/main.js') }}"></script>

    <!-- Larevel -->
    @yield('head')

    <!-- END BLOCK : HeaderContents -->
    <!-- START BLOCK : AdminContents -->
    {{--{IncludeFiles}--}}
    <!-- START BLOCK : LoginScreen -->
    <script language="javascript">
        $(document).ready(function(){
            $.colorbox({inline:true, href:"#admin_login", innerWidth:390, innerHeight:350, overlayClose: false, escKey: false, closeButton: false, open: true, opacity: "0.85"});
            $('#cboxClose').remove();
            $(".SkytzCreateSpace").remove();
            $(".SkytzCms").remove();
            $('#cboxOverlay').css('background-color', '#58ACE0');
            $('body').bind('contextmenu', function(e) {
                return false;
            });
            $('#SkytzConnectTo').val('{sitename}');
        });
    </script>
    {{--<script type="text/javascript" src="/controller/jquery/jqueryLogin.js"></script>--}}
    <!-- END BLOCK : LoginScreen -->
    {{--{DragDropBlockFiles}--}}
    {{--{CkEditor}--}}
    <!-- END BLOCK : AdminContents -->
    <!-- START BLOCK : GoogleAnalytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '{tracking-id}', '{server}');
        ga('send', 'pageview');

    </script>
    <!-- END BLOCK : GoogleAnalytics -->
</head>
<body>

{{-- Skytz CMS Header --}}
@yield('header_cms')

<div class="SkytzCreateSpace"></div>
<!-- SKYTZ CMS -->
<!-- END BLOCK : ShowAdminOptions -->
<div class="row">

    <!-- START CONTAINER -->
    <div class="container">


        <!-- START HEADER -->
        <div class="header">


            <div class="row">


                <div class="small-12 medium-4 columns logo">

                    <a href="/"><img src="{{ template_url('/img/logo.png') }}" alt="CMS demo"></a>

                </div>


                <div id="hamburger">

                    <img src="{{ template_url('/img/hamburger.png') }}" alt="Menu" />

                    <h2>Menu</h2>

                </div>



                <div class="small-12 medium-12 large-8 columns menu">

                    <ul>
                        <!-- START BLOCK : MenuItems -->
                        <li><a href="{url}">{menuitem}</a>{submenu}</li>
                        <!-- END BLOCK : MenuItems -->
                    </ul>

                </div>


            </div>


        </div>
        <!-- END HEADER -->


        <!-- START CONTENT -->
        <div class="content">

            <div class="row">

                <!-- START LEFT SIDE -->
                <div class="small-12 medium-12 large-12 columns left">

                    <div class="row">

                        <!-- START TEKST -->
                        <div class="tekst">

                            <!-- START BLOCK : Slider -->
                            <div class="slider-wrapper" style="height: {height_wrapper}px; display: {display};">
                                <div id="slider" style="height:{height_slider}px;">
                                    {{--{slider}--}}
                                </div>
                                <div id="slider-direction-nav"></div>
                                <div id="slider-control-nav"></div>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    var slider = $('#slider').leanSlider({
                                        directionNav: '#slider-direction-nav',
                                        controlNav: '#slider-control-nav'
                                    });
                                });
                            </script>
                            <div class="image"><img src="{{ template_url('/img/header.png') }}" alt=""></div>

                            <h1>Skytz Demosite</h1>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis facilisis tortor non ligula euismod, quis euismod quam maximus. Nulla varius et ipsum vitae volutpat. Morbi cursus dolor in lorem semper viverra. Aenean euismod malesuada nisi, vitae placerat felis facilisis a. Mauris nec erat nec elit posuere condimentum eu vitae ex. Nullam urna ex, tempor vitae ultricies id, fermentum vitae dolor. Sed vehicula, dui et mattis blandit, lorem elit mattis lorem, a imperdiet lorem tellus nec est. In posuere nibh in ligula tincidunt pretium. Proin id fringilla neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Suspendisse quis enim lacus. Sed ultricies neque a ultricies auctor. Integer eu risus vitae dolor elementum volutpat. Maecenas ut lorem sed quam tempus tempus. Etiam iaculis mi id luctus malesuada. Nunc dapibus lorem id ipsum egestas faucibus.</p>

                            <p>Nulla arcu magna, mattis nec enim non, scelerisque tincidunt turpis. In dictum nibh eget nulla lobortis, vel efficitur diam tristique. Curabitur ullamcorper, elit at suscipit dignissim, leo justo fringilla tortor, et blandit massa enim vel massa. Morbi a lobortis diam. Nunc egestas dui egestas, placerat nisi vel, suscipit urna. Curabitur eros eros, interdum eget dapibus id, lobortis eget nisi. Quisque mattis quam eu semper vulputate. Cras semper, dui at luctus luctus, justo orci egestas nisl, dapibus luctus eros est et magna. Duis luctus interdum enim, at rhoncus quam egestas sit amet. Nunc ut convallis velit, ut lobortis tortor. Ut rhoncus sapien et mi scelerisque, eget tempus lectus consectetur. Phasellus sagittis, erat sit amet suscipit faucibus, dui tortor elementum ipsum, a ullamcorper quam massa eu metus. Pellentesque blandit elit eget scelerisque tempus.</p>
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
                        <!-- END TEKST -->

                    </div>

                </div>
                <!-- END LEFT SIDE -->

            </div>


        </div>
        <!-- END CONTENT -->



    </div>
    <!-- END CONTAINER -->

</div>

<div class="bottom">
    <div class="row ">
        <div class="small-12 columns sitemap">
            <p>Copyright 2014 <a href="/">Skytz.nl</a>  |  Uw eigen website gemakkelijk beheren</p>
        </div>
    </div>
</div>

<script src="{{ template_url('/js/foundation.min.js') }}"></script>
<script>
    $(document).foundation();
</script>

@yield('bottom')

</body>
</html>