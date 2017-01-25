
<div id="html-block-{{ $id }}" class="html_block">
    @if ($htmlBlock = HtmlBlock::find($id))
        {!! $htmlBlock->html !!}

    @else
        <p>Dit html blok bestaat niet meer / is verwijderd.</p>
    @endif
</div>
