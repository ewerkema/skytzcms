<header>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid navbar-background">
            <div class="navbar-header flex-left">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand hidden-xs" href="{{ cms_url('/') }}">
                    <img src="{{ url('images/skytz_logo.png') }}" alt="" class="img-responsive">
                    {{ config('app.name', 'Skytz CMS') }}
                </a>

                <div class="divider hidden-xs"></div>

                <ul class="nav navbar-nav">
                    @if (!Auth::guest())
                        <li class="dropdown dropdown-large">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-list"></span>
                                Selecteer pagina ({{ count(Page::all()) }}) <span class="caret"></span>
                            </a>
                            <ul class="nav dropdown-menu dropdown-menu-large row">
                                <li class="small-6 columns">
                                    <ul>
                                        <li class="dropdown-header">Pagina's in menu</li>
                                        @foreach (Page::getMenuWithSubpages() as $page)

                                            <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                <a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a>
                                            </li>

                                            @if (isset($page->subpages))
                                                @foreach ($page->subpages as $subpage)
                                                    <li class="{{ (isset($currentPage) && $subpage->id == $currentPage->id) ? "active" : "" }}">
                                                        <a href="{{ page_url($subpage->getSlug()) }}">{{ $page->title }} > {{ $subpage->title }}</a>
                                                    </li>
                                                @endforeach
                                            @endif

                                        @endforeach

                                        @if (empty(Page::getMenuWithSubpages()))
                                            <li><a href="#">Geen pagina's gevonden.</a></li>
                                        @endif
                                    </ul>
                                </li>
                                <li class="small-6 columns">
                                    <ul>
                                        <li class="dropdown-header">Losse pagina's</li>

                                        @foreach (Page::getNonMenu() as $page)
                                            <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                <a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a>
                                            </li>
                                        @endforeach

                                        @if (empty(Page::getNonMenu()))
                                            <li><a href="#">Geen losse pagina's gevonden.</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>

                        </li>
                        <li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#newPageModal"><span class="glyphicon glyphicon-plus"></span> Nieuwe pagina</a></li>
                    @endif
                </ul>
            </div>

            <div class="flex-center">
                <ul class="nav navbar-nav flex-center">
                    @if (!Auth::guest())
                        <li><a href="#" data-toggle="modal" data-target="#"><span class="glyphicon glyphicon-sort"></span> Menu indelen</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#mediaModal"><span class="glyphicon glyphicon-picture"></span> Media uploaden</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                Mijn modules (4) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" data-target="#moduleContactModal">Contact formulier</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#moduleAlbumsModal">Foto albums</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#moduleArticlesModal">Nieuws</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#moduleSlidersModal">Sliders</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="collapse navbar-collapse flex-right" id="app-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ cms_url('/login') }}">Inloggen</a></li>
                        <li><a href="{{ cms_url('/register') }}">Registreren</a></li>
                    @else
                        <li class="publish"><a href="#" onclick="publishWebsite()"><span class="glyphicon glyphicon-globe"></span> Publiceer website</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-question-sign"></span> Help</a></li>
                        <li class="visible-xs">
                            <a href="{{ cms_url('/logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                            >
                                Uitloggen
                            </a>

                            <form id="logout-form" action="{{ cms_url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                <span id="userName">{{ Auth::user()->getName() }}</span> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" data-target="#websiteModal">Website instellingen</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#accountModal">Account instellingen</a></li>
                                <li>
                                    <a href="{{ cms_url('/logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();"
                                    >
                                        Uitloggen
                                    </a>

                                    <form id="logout-form" action="{{ cms_url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="navbar-header flex-center">

                <ul class="nav navbar-nav">
                    @if (!Auth::guest())
                        <li id="changePage"><a href="#" onclick="changePage();"><span class="glyphicon glyphicon-pencil"></span> Pagina bewerken</a></li>
                        <li id="saveChanges"><a href="#" onclick="saveChanges();"><span class="glyphicon glyphicon-ok"></span> Pagina opslaan</a></li>
                        <li id="revertChanges"><a href="#" onclick="revertChanges();"><span class="glyphicon glyphicon-remove"></span> Wijzigingen annuleren</a></li>
                        <li id="hideLayout"><div class="divider hidden-xs"></div></li>
                        <li id="changeLayout"><a href="#" onclick="changeLayout();"><span class="glyphicon glyphicon-th"></span> Indeling bewerken</a></li>
                        <li id="saveLayout"><a href="#" onclick="saveLayout();"><span class="glyphicon glyphicon-ok"></span> Indeling opslaan</a></li>
                        <li><div class="divider hidden-xs"></div></li>
                        <li><a href="#" data-toggle="modal" data-target="#pageModal"><span class="glyphicon glyphicon-cog"></span> Pagina instellingen</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>