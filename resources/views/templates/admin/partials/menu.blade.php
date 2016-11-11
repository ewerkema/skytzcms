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