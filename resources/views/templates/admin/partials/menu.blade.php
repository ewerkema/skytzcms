<nav class="small-12 medium-12 large-12 columns menu">
    <ul>
        @foreach (Menu::getMenuWithSubpages() as $menuItem)
            <li class="{{ (isset($currentPage) && $menuItem->page_id != null && $menuItem->page->id == $currentPage->id) ? "active" : "" }}{{ ($menuItem->subItems->count()) ? " has-dropdown" : "" }}">

                <a href="{{ $menuItem->getUrl() }}" target="{{ $menuItem->open_in_new_tab ? '_BLANK' : '_SELF' }}">{{ $menuItem->linkName }}</a>

                @if ($menuItem->subItems->count())
                    <ul class="dropdown">
                        @foreach ($menuItem->subItems as $subMenuItem)
                            <li class="{{ (isset($currentPage) && $subMenuItem->page_id != null && $subMenuItem->page->id == $currentPage->id) ? "active" : "" }}">
                                <a href="{{ $subMenuItem->getUrl() }}" target="{{ $subMenuItem->open_in_new_tab ? '_BLANK' : '_SELF' }}">{{ $subMenuItem->linkName }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>