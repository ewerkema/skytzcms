
<div class="page-content" data-page="{{ $page->id }}">
    @if (sizeof($page->content) == 0)
        <div class="block" data-name="block0" data-editable></div>
    @endif

    @php ($row = 0)
    @foreach ($page->content as $name => $col)
        <div class="block columns medium-{{ $col['width'] }} medium-offset-{{ $col['offset'] }} {{ ($row != $col['row']) ? "clear" : "" }}" data-name="{{ $name }}" data-editable>
            {!! $col['content'] !!}
        </div>
        @php ($row = $col['row'])
    @endforeach
</div>

<script type="text/javascript">

    function loadPageContent() {
        var blockContent = $('.page-content');
        blockContent.html("");
        $.get('/cms/pages/{{ $page->id }}/content', function(content) {
            if (content.length == 0) {
                content.html('<div class="block" data-name="block0" data-editable></div>');
            }

            var row = 0;
            _.each(content, function (item, name) {
                var newRow = (row != item['row']) ? "clear" : "";
                var element = '<div class="block columns medium-'+item['width']+' medium-offset-'+item['offset']+' '+newRow+'" data-name="'+name+'" data-editable>'+item['content']+'</div>';

                blockContent.append(element);
            });
        });
    }

</script>