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
                    <img src="{{ url('/css/images/skytz_logo.png') }}" alt="" class="img-responsive">
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
                                @php ($chunk = (Page::getMenu()->count() > 10) ? 10 : 5)
                                @foreach (Page::getMenu()->chunk($chunk) as $pageChunk)
                                    <li class="small-4 columns">
                                        <ul>
                                            <li class="dropdown-header">Pagina's in menu</li>
                                            @foreach ($pageChunk as $page)
                                                <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                    <a href="{{ page_url($page->getSlug()) }}">{{ (($page->parent()->first()) ? $page->parent()->first()->title . ' > ' : '' ).$page->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                                @if (empty(Page::getMenuWithSubpages()))
                                    <li class="small-6 columns">
                                        <ul>
                                            <li class="dropdown-header">Pagina's in menu</li>
                                            <li>Geen pagina's gevonden.</li>
                                        </ul>
                                    </li>
                                @endif
                                <li class="small-{{ (Page::getMenu()->count() <= 5) ? 8 : 4 }} columns">
                                    <ul>
                                        <li class="dropdown-header">Losse pagina's</li>

                                        @foreach (Page::getNonMenu() as $page)
                                            <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                <a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a>
                                            </li>
                                        @endforeach

                                        @if (!Page::getNonMenu()->count())
                                            <li>Geen losse pagina's gevonden.</li>
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
                        <li><a href="#" data-toggle="modal" data-target="#sortMenuModal"><span class="glyphicon glyphicon-sort"></span> Menu indelen</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#uploadMediaModal"><span class="glyphicon glyphicon-upload"></span> Media uploaden</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#mediaModal"><span class="glyphicon glyphicon-picture"></span> Media overzicht</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                Mijn modules ({{ Module::where('active', 1)->count() }}) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @foreach (Module::where('active', 1)->get() as $module)
                                    <li><a href="#" data-toggle="modal" data-target="#module{{ ucfirst($module->template) }}Modal">Module {{ $module->name }}</a></li>
                                @endforeach
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
                        <li><a href="#" onclick="showHelp()"><span class="glyphicon glyphicon-question-sign"></span></a></li>
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
                            <a href="#" class="dropdown-toggle highlight" data-toggle="dropdown" role="button" aria-expanded="false">
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

                <ul class="nav navbar-nav navbar-wide flex-center">
                    @if (!Auth::guest())
                        <li id="changePage"><a href="#" onclick="changePage();"><span class="glyphicon glyphicon-pencil"></span> Pagina bewerken</a></li>
                        <li id="saveChanges"><a href="#" onclick="saveChanges();"><span class="glyphicon glyphicon-ok"></span> Pagina opslaan</a></li>
                        <li id="revertChanges"><a href="#" onclick="revertChanges();"><span class="glyphicon glyphicon-remove"></span> Wijzigingen annuleren</a></li>
                        <li id="hideLayout"><div class="divider hidden-xs"></div></li>
                        <li id="changeLayout"><a href="#" onclick="changeLayout();"><span class="glyphicon glyphicon-th"></span> Blokken bewerken</a></li>
                        <li id="saveLayout"><a href="#" onclick="saveLayout();"><span class="glyphicon glyphicon-ok"></span> Blokken opslaan</a></li>
                        <li id="saveLayout"><a href="#" onclick="cancelLayout();"><span class="glyphicon glyphicon-remove"></span> Wijzigingen annuleren</a></li>
                        <li><div class="divider hidden-xs"></div></li>
                        <li><a href="#" data-toggle="modal" data-target="#pageModal"><span class="glyphicon glyphicon-cog"></span> Pagina instellingen</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>