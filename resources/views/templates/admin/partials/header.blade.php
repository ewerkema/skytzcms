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
                                Selecteer pagina ({{ count($menu)+count($nonmenu) }}) <span class="caret"></span>
                            </a>
                            <ul class="nav dropdown-menu dropdown-menu-large row">
                                <li class="small-6 columns">
                                    <ul>
                                        <li class="dropdown-header">Pagina's in menu</li>
                                        @foreach ($menu as $menupage)
                                            <li class="{{ (isset($page) && $menupage->id == $page->id) ? "active" : "" }}">
                                                <a href="{{ page_url($menupage->slug) }}">{{ $menupage->title }}</a>
                                            </li>
                                        @endforeach

                                        @if (empty($menu))
                                            <li><a href="#">Geen pagina's gevonden.</a></li>
                                        @endif
                                    </ul>
                                </li>
                                <li class="small-6 columns">
                                    <ul>
                                        <li class="dropdown-header">Losse pagina's</li>

                                        @foreach ($nonmenu as $nonmenupage)
                                            <li class="{{ (isset($page) && $nonmenupage->id == $page->id) ? "active" : "" }}">
                                                <a href="{{ page_url($nonmenupage->slug) }}">{{ $nonmenupage->title }}</a>
                                            </li>
                                        @endforeach

                                        @if (empty($nonmenu))
                                            <li><a href="#">Geen losse pagina's gevonden.</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>

                        </li>
                        <li class="hidden-xs"><a href="#"><span class="glyphicon glyphicon-plus"></span> Nieuwe pagina</a></li>
                    @endif
                </ul>
            </div>

            <div class="collapse flex-center">
                <ul class="nav navbar-nav flex-center">
                    @if (!Auth::guest())
                        <li><a href="#"><span class="glyphicon glyphicon-picture"></span> Media uploaden</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                Mijn modules (3) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ cms_url('/index') }}">Contact formulier</a></li>
                                <li><a href="{{ cms_url('/index') }}">Foto album</a></li>
                                <li><a href="{{ cms_url('/index') }}">Nieuws</a></li>
                                <li><a href="{{ cms_url('/index') }}">Google Analytics (statistieken)</a></li>
                                <li><a href="{{ cms_url('/index') }}">Slider</a></li>
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
                        <li class="publish"><a href=""><span class="glyphicon glyphicon-globe"></span> Publiceer website</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-question-sign"></span> Help</a></li>
                        <li class="visible-xs">
                            <a href="{{ cms_url('/logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                Uitloggen
                            </a>

                            <form id="logout-form" action="{{ cms_url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                {{ Auth::user()->getName() }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ cms_url('/index') }}">Website instellingen</a></li>
                                <li><a href="{{ cms_url('/index') }}">Account instellingen</a></li>
                                <li>
                                    <a href="{{ cms_url('/logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
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
                        <li><a href="#pagina-beheer" data-toggle="modal" data-target="#pagesModal"><span class="glyphicon glyphicon-cog"></span> Pagina instellingen</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>