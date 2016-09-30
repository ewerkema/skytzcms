<header>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header flex-left">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ cms_url('/') }}">
                    <img src="{{ url('images/skytz_logo.png') }}" alt="" class="img-responsive">
                    {{ config('app.name', 'Skytz CMS') }}
                </a>

                <div class="divider"></div>

                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (!Auth::guest())
                        <li class="dropdown dropdown-large">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-list"></span>
                                Selecteer pagina (13) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-large row">
                                <li class="small-4 columns">
                                    <ul>
                                        <li class="dropdown-header">Pagina's in menu</li>
                                        <li><a href="{{ cms_url('/home') }}">Home</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Over ons</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Assortiment</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Contact</a></li>
                                    </ul>
                                </li>
                                <li class="small-4 columns">
                                    <ul>
                                        <li class="dropdown-header">Losse pagina's</li>
                                        <li><a href="{{ cms_url('/home') }}">Disclaimer</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Algemene voorwaarden</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Assortiment</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Contact</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Test pagina</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Algemene voorwaarden</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Assortiment</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Contact</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Test pagina</a></li>
                                    </ul>
                                </li>
                                <li class="small-4 columns">
                                    <ul>
                                        <li class="dropdown-header">Overig</li>
                                        <li><a href="{{ cms_url('/home') }}">Disclaimer</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Algemene voorwaarden</a></li>
                                        <li><a href="{{ cms_url('/home') }}">Assortiment</a></li>
                                    </ul>
                                </li>
                            </ul>

                        </li>
                        <li><a href="#"><span class="glyphicon glyphicon-plus"></span> Nieuwe pagina</a></li>
                    @endif
                </ul>
            </div>

            <div class="collapse navbar-collapse flex-center" id="app-navbar-collapse">

                <ul class="nav navbar-nav flex-center">
                    @if (!Auth::guest())
                        <li><a href="#"><span class="glyphicon glyphicon-picture"></span> Media uploaden</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Instellingen</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                Mijn modules (3) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ cms_url('/home') }}">Contact formulier</a></li>
                                <li><a href="{{ cms_url('/home') }}">Foto album</a></li>
                                <li><a href="{{ cms_url('/home') }}">Nieuws</a></li>
                                <li><a href="{{ cms_url('/home') }}">Google Analytics (statistieken)</a></li>
                                <li><a href="{{ cms_url('/home') }}">Slider</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="collapse navbar-collapse flex-right">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ cms_url('/login') }}">Inloggen</a></li>
                        <li><a href="{{ cms_url('/register') }}">Registreren</a></li>
                    @else
                        <li><a href=""><span class="glyphicon glyphicon-globe"></span> Publiceer website</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                {{ Auth::user()->getName() }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ cms_url('/home') }}">Website instellingen</a></li>
                                <li><a href="{{ cms_url('/home') }}">Account instellingen</a></li>
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
    </nav>
</header>