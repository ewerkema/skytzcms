<nav aria-label="You are here:" role="navigation">
    <ul class="breadcrumbs">
        <li><a href="{{ page_url('/') }}">Home</a>
        @if (URL::previous() == URL::current())
            @if ($page = Page::find($currentPage->id))
                <li><a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a></li>
            @endif
        @else
            @if (!empty(URL::previous()))
                <li><a href="{{ URL::previous() }}">{{ Page::whereSlug(basename(URL::previous()))->first()->title }}</a></li>
            @endif

            @if ($page = Page::find($currentPage->id))
                <li><a href="{{ page_url($page->getSlug()) }}">{{ $page->title }}</a></li>
            @endif
        @endif
    </ul>
</nav>