<div class="page-content" data-page="{{ $currentPage->id }}">

    @foreach ($currentPage->getPublishedContent() as $row)
        <div class="row">
            @foreach ($row as $block)
                @if ($block['module'])
                    <div class="block columns medium-{{ $block['width'] }} medium-offset-{{ $block['offset'] }}">
                        @include('templates.admin.modules.'.Module::find($block['module'])->template, ['id' => $block['module_id']])
                    </div>
                @else
                    <div class="block columns medium-{{ $block['width'] }} medium-offset-{{ $block['offset'] }}">
                        {!! $block['content'] !!}
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
</div>