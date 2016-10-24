<div class="page-content" data-page="{{ $page->id }}">

    @foreach ($page->getPublishedContent() as $row)
        <div class="row">
            @foreach ($row as $block)
                <div class="block columns medium-{{ $block['width'] }} medium-offset-{{ $block['offset'] }}">
                    {!! $block['content'] !!}
                </div>
            @endforeach
        </div>
    @endforeach
</div>