<nav class="small-12 medium-12 large-12 columns menu">
    <ul>
        @foreach (Page::getMenuWithSubpages() as $page)
            <li class="{{ (isset($currentPage) && $page->id == $currentPage->id) ? "active" : "" }}{{ ($page->subpages->count()) ? " has-dropdown" : "" }}">

                <a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a>

                @if ($page->subpages->count())
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