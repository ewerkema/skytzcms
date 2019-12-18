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
                                @php
                                    $menuPages = Page::getMenu();
                                    $nonMenuPages = Page::getNonMenu();

                                    if ($menuPages->count() > $nonMenuPages->count()) {
                                        $menuPageChunkSize = max(10, floor($menuPages->count() / 2));
                                        $nonMenuPageChunkSize = $nonMenuPages->count();
                                    } else {
                                        $menuPageChunkSize = $menuPages->count();
                                        $nonMenuPageChunkSize = max(10, floor($nonMenuPages->count() / 2));
                                    }
                                @endphp
                                @if (empty(Menu::getMenuWithSubpages()))
                                    <li class="small-12 medium-4 columns">
                                        <ul>
                                            <li class="dropdown-header">Pagina's in menu</li>
                                            <li>Geen pagina's gevonden.</li>
                                        </ul>
                                    </li>
                                @else
                                    @foreach ($menuPages->chunk($menuPageChunkSize) as $pageChunk)
                                        <li class="small-12 medium-4 columns">
                                            <ul>
                                                <li class="dropdown-header">Pagina's in menu</li>
                                                @foreach ($pageChunk as $page)
                                                    <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                        <a href="{{ $page->getUrl() }}">{{ $page->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif

                                @if (!$nonMenuPages->count())
                                    <li class="small-12 medium-4 columns">
                                        <ul>
                                            <li class="dropdown-header">Losse pagina's</li>
                                            <li>Geen losse pagina's gevonden.</li>
                                        </ul>
                                    </li>
                                @else
                                    @foreach ($nonMenuPages->chunk($nonMenuPageChunkSize) as $pageChunk)
                                        <li class="small-12 medium-4 columns">
                                            <ul>
                                                <li class="dropdown-header">Losse pagina's</li>

                                                @foreach ($pageChunk as $page)
                                                    <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}">
                                                        <a href="{{ $page->getUrl() }}">{{ $page->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                        </li>
                        <li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#newPageModal"><span class="glyphicon glyphicon-plus"></span></a></li>
                        <li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#uploadMediaModal"><span class="glyphicon glyphicon-upload"></span></a></li>
                    @endif
                </ul>
            </div>

            @php
                $modules = Module::where('active', 1)->get()
            @endphp

            <div class="flex-center flex-space-between-sm">
                <ul class="nav navbar-nav flex-center flex-space-between-sm">
                    @if (!Auth::guest())
                        <li><a href="#" data-toggle="modal" data-target="#mediaModal"><span class="glyphicon glyphicon-picture"></span> <span class="hidden-xs">Media overzicht</span></a></li>
                        <li class="visible-xs"><a href="#" data-toggle="collapse" data-target="#module-navbar-collapse"><span class="glyphicon glyphicon-th-large"></span> <span class="caret"></span></a></li>
                        <li class="dropdown hidden-xs">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                <span class="hidden-xs">Mijn modules ({{ $modules->count() }})</span> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @foreach ($modules as $module)
                                    <li><a href="#" data-toggle="modal" data-target="#module{{ ucfirst(camel_case(str_replace("_", " ", $module->template))) }}Modal">Module {{ $module->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @if (Auth::user()->isAn('admin'))
                            <li><a href="#" data-toggle="modal" data-target="#usersModal"><span class="glyphicon glyphicon-user"></span> <span class="hidden-xs">Gebruikersbeheer</span></a></li>
                        @endif
                    @endif
                </ul>
            </div>

            <div class="visible-xs row">
                <div class="collapse navbar-collapse flex-right" id="module-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        @foreach ($modules as $module)
                            <li><a href="#" data-toggle="modal" data-target="#module{{ ucfirst(camel_case(str_replace("_", " ", $module->template))) }}Modal">Module {{ $module->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="collapse navbar-collapse flex-right" id="app-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ cms_url('/login') }}">Inloggen</a></li>
                    @else
                        <li class="dropdown publish">
                            <a href="#" class="dropdown-toggle highlight" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-globe"></span> Publiceren <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" onclick="publishPage()">Huidge pagina</a></li>
                                <li><a href="#" data-toggle="modal" onclick="publishWebsite()">Volledige website</a></li>
                            </ul>
                        </li>
                        <li class="visible-xs">
                            <a href="{{ cms_url('/logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form-mobile').submit();"
                            >
                                Uitloggen
                            </a>

                            <form id="logout-form-mobile" action="{{ cms_url('/logout') }}" method="POST" style="display: none;">
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
                                <li><a href="#" data-toggle="modal" data-target="#menuModal">Menu instellingen</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#headersModal">Header instellingen</a></li>
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
            <div class="navbar-header flex-center flex-space-between-sm">

                <ul class="nav navbar-nav navbar-wide flex-center flex-space-between-sm">
                    @if (!Auth::guest())
                        <li id="changePage"><a href="#" onclick="changePage();"><span class="glyphicon glyphicon-pencil"></span> <span class="hidden-xs">Pagina bewerken</span></a></li>
                        <li id="saveChanges"><a href="#" onclick="saveChanges();"><span class="glyphicon glyphicon-ok"></span> <span class="hidden-xs">Pagina opslaan</span></a></li>
                        <li id="revertChanges"><a href="#" onclick="revertChanges();"><span class="glyphicon glyphicon-remove"></span> <span class="hidden-xs">Wijzigingen annuleren</span></a></li>
                        <li id="hideLayout"><div class="divider hidden-xs"></div></li>
                        <li id="changeLayout"><a href="#" onclick="changeLayout();"><span class="glyphicon glyphicon-th"></span> <span class="hidden-xs">Blokken bewerken</span></a></li>
                        <li id="saveLayout"><a href="#" onclick="saveLayout();"><span class="glyphicon glyphicon-ok"></span> <span class="hidden-xs">Blokken opslaan</span></a></li>
                        <li id="saveLayout"><a href="#" onclick="cancelLayout();"><span class="glyphicon glyphicon-remove"></span> <span class="hidden-xs">Wijzigingen annuleren</span></a></li>
                        <li><div class="divider hidden-xs"></div></li>
                        <li><a href="#" data-toggle="modal" data-target="#pageModal"><span class="glyphicon glyphicon-cog"></span> <span class="hidden-xs">Pagina instellingen</span></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>