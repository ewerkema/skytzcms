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
                <a class="navbar-brand hidden-xs" href="<?php echo e(cms_url('/')); ?>">
                    <img src="<?php echo e(url('images/skytz_logo.png')); ?>" alt="" class="img-responsive">
                    <?php echo e(config('app.name', 'Skytz CMS')); ?>

                </a>

                <div class="divider hidden-xs"></div>

                <ul class="nav navbar-nav">
                    <?php if(!Auth::guest()): ?>
                        <li class="dropdown dropdown-large">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-list"></span>
                                Selecteer pagina (<?php echo e(count(Page::all())); ?>) <span class="caret"></span>
                            </a>
                            <ul class="nav dropdown-menu dropdown-menu-large row">
                                <?php ($chunk = (Page::getMenu()->count() > 10) ? 10 : 5); ?>
                                <?php $__currentLoopData = Page::getMenu()->chunk($chunk); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageChunk): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <li class="small-4 columns">
                                        <ul>
                                            <li class="dropdown-header">Pagina's in menu</li>
                                            <?php $__currentLoopData = $pageChunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                                <li class="<?php echo e((isset($currentPage) && $page->id == $currentPage->id) ? "active" : ""); ?>">
                                                    <a href="<?php echo e(page_url($page->getSlug())); ?>"><?php echo e((($page->parent()->first()) ? $page->parent()->first()->title . ' > ' : '' ).$page->title); ?></a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        </ul>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                <?php if(empty(Page::getMenuWithSubpages())): ?>
                                    <li class="small-6 columns">
                                        <ul>
                                            <li class="dropdown-header">Pagina's in menu</li>
                                            <li>Geen pagina's gevonden.</li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <li class="small-4 columns">
                                    <ul>
                                        <li class="dropdown-header">Losse pagina's</li>

                                        <?php $__currentLoopData = Page::getNonMenu(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <li class="<?php echo e((isset($currentPage) && $page->id == $currentPage->id) ? "active" : ""); ?>">
                                                <a href="<?php echo e(page_url($page->getSlug())); ?>"><?php echo e($page->title); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                        <?php if(!Page::getNonMenu()->count()): ?>
                                            <li>Geen losse pagina's gevonden.</li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            </ul>

                        </li>
                        <li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#newPageModal"><span class="glyphicon glyphicon-plus"></span> Nieuwe pagina</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="flex-center">
                <ul class="nav navbar-nav flex-center">
                    <?php if(!Auth::guest()): ?>
                        <li><a href="#" data-toggle="modal" data-target="#sortMenuModal"><span class="glyphicon glyphicon-sort"></span> Menu indelen</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#mediaModal"><span class="glyphicon glyphicon-picture"></span> Media uploaden</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-th-large"></span>
                                Mijn modules (<?php echo e(Module::where('active', 1)->count()); ?>) <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <?php $__currentLoopData = Module::where('active', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <li><a href="#" data-toggle="modal" data-target="#module<?php echo e(ucfirst($module->template)); ?>Modal">Module <?php echo e($module->name); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="collapse navbar-collapse flex-right" id="app-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <?php if(Auth::guest()): ?>
                        <li><a href="<?php echo e(cms_url('/login')); ?>">Inloggen</a></li>
                        <li><a href="<?php echo e(cms_url('/register')); ?>">Registreren</a></li>
                    <?php else: ?>
                        <li class="publish"><a href="#" onclick="publishWebsite()"><span class="glyphicon glyphicon-globe"></span> Publiceer website</a></li>
                        <li><a href="#" onclick="showHelp()"><span class="glyphicon glyphicon-question-sign"></span></a></li>
                        <li class="visible-xs">
                            <a href="<?php echo e(cms_url('/logout')); ?>"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                            >
                                Uitloggen
                            </a>

                            <form id="logout-form" action="<?php echo e(cms_url('/logout')); ?>" method="POST" style="display: none;">
                                <?php echo e(csrf_field()); ?>

                            </form>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a href="#" class="dropdown-toggle highlight" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                <span id="userName"><?php echo e(Auth::user()->getName()); ?></span> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" data-target="#websiteModal">Website instellingen</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#accountModal">Account instellingen</a></li>
                                <li>
                                    <a href="<?php echo e(cms_url('/logout')); ?>"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();"
                                    >
                                        Uitloggen
                                    </a>

                                    <form id="logout-form" action="<?php echo e(cms_url('/logout')); ?>" method="POST" style="display: none;">
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="navbar-header flex-center">

                <ul class="nav navbar-nav navbar-wide flex-center">
                    <?php if(!Auth::guest()): ?>
                        <li id="changePage"><a href="#" onclick="changePage();"><span class="glyphicon glyphicon-pencil"></span> Pagina bewerken</a></li>
                        <li id="saveChanges"><a href="#" onclick="saveChanges();"><span class="glyphicon glyphicon-ok"></span> Pagina opslaan</a></li>
                        <li id="revertChanges"><a href="#" onclick="revertChanges();"><span class="glyphicon glyphicon-remove"></span> Wijzigingen annuleren</a></li>
                        <li id="hideLayout"><div class="divider hidden-xs"></div></li>
                        <li id="changeLayout"><a href="#" onclick="changeLayout();"><span class="glyphicon glyphicon-th"></span> Blokken bewerken</a></li>
                        <li id="saveLayout"><a href="#" onclick="saveLayout();"><span class="glyphicon glyphicon-ok"></span> Blokken opslaan</a></li>
                        <li><div class="divider hidden-xs"></div></li>
                        <li><a href="#" data-toggle="modal" data-target="#pageModal"><span class="glyphicon glyphicon-cog"></span> Pagina instellingen</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>