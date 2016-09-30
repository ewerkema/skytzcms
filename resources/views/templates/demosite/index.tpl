<!doctype html>
<html class="no-js" lang="en">
  <head>
  <!-- START BLOCK : HeaderContents -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{meta_title}</title>
    <link rel="stylesheet" href="/view/templates/demosite/css/foundation.css" />
    <link rel="stylesheet" href="/view/templates/demosite/css/style.css" />
    <script src="/view/templates/demosite/js/vendor/modernizr.js"></script>
    
    <!-- jQuery -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	
	<!-- Main jQuery -->
	<script src="/view/templates/demosite/css/main.js"></script>

  <!-- END BLOCK : HeaderContents -->    
  <!-- START BLOCK : AdminContents -->
    {IncludeFiles}
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
	  <script type="text/javascript" src="/controller/jquery/jqueryLogin.js"></script>
    <!-- END BLOCK : LoginScreen -->
    {DragDropBlockFiles}
    {CkEditor}
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
  <!-- START BLOCK : ShowAdminOptions -->
  <div id="xToolbar" style="display:none;"></div>
  <!-- SKYTZ CMS -->
    <div class="SkytzCms">
        {AdminContents}
    </div>
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
	  			
	  				<a href="/"><img src="/view/templates/demosite/img/logo.png" alt="CMS demo"></a>
	  			
	  			</div>
	  			
	  			
	  			<div id="hamburger">
	  					
	  					<img src="/view/templates/demosite/img/hamburger.png" alt="Menu" />
	  					
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
								{slider}
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
						{image}
						<!-- END BLOCK : Slider -->
		  				
            <!-- START BLOCK : NewsItems -->
                <strong>{title}</strong> <br /> <br />
                <p>{item}</p>
            <!-- END BLOCK : NewsItems -->


            <!-- START BLOCK : StrokeList -->
            <div class="columns {width}" id="stroke_{id}">
              <!-- START BLOCK : Blocks -->
                <!-- START BLOCK : Options -->
                  <div class="options" linked="arrayorder_{id}"><a href="/controller/blocks/AddModule/?Bid={id}" class="AddModule"><img src="/controller/images/module-toevoegen.png" style="float: left;" class="vtip" title="Module toevoegen aan tekstvlak"></a> <a href="/controller/blocks/visibleBlock/?Bid={id}" class="visibleBlock"><img src="/controller/images/{visible_icon}.png" class="vtip" title="Zichtbaarheid bewerken"></a> <!--<a href="/controller/blocks/stuckBlock/?Bid={id}" class="stuckBlock"><img src="/controller/images/{stuck_icon}.png" class="vtip" title="Tekstvlak vast zetten"></a>--> <a href="/controller/blocks/deleteBlock/?Bid={id}" class="DeleteBlock"><img src="/controller/images/verwijderen.png" class="vtip" title="Tekstvlak verwijderen"></a> <a href="/controller/blocks/editBlock/?Bid={id}" class="EditBlock"><img src="/controller/images/bewerken.png" class="vtip" title="Tekstvlak bewerken"></a></div>
                <!-- END BLOCK : Options -->
              <div class="portlet" id="arrayorder_{id}"{BlockAdminOptions}>
                  {content}
              </div>
              <!-- END BLOCK : Blocks -->
            </div>
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
  	
  		<div class="row">
  		
  			<div class="small-12 columns sitemap">
  			
  				<p>Copyright 2014 <a href="/">Skytz.nl</a>  |  Uw eigen website gemakkelijk beheren</p>
  				

  			</div>

  		</div>
  	
  	</div>

    <script src="/view/templates/demosite/js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>