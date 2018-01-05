@if (isset($article) && $article)
    @include('templates.admin.modules.single_article')
@elseif (isset($project) && $project)
    @include('templates.admin.modules.single_project')
@else

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
@endif