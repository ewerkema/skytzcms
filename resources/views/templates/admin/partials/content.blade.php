
<div class="page-content" data-page="{{ $page->id }}">
    @if (sizeof($page->content) == 0)
        <div class="block" data-name="block0" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>
    @endif

    @foreach ($page->getContentFront() as $row)
        <div class="row">
            @foreach ($row as $block)
                <div class="block columns medium-{{ $block['width'] }} medium-offset-{{ $block['offset'] }}" data-name="{{ $block['name'] }}" data-gs-x="{{ $block['x'] }}" data-gs-y="{{ $block['y'] }}" data-gs-width="{{ $block['width'] }}" data-gs-height="{{ $block['height'] }}" data-module="{{ $block['module'] }}" data-editable>
                    {!! $block['content'] !!}
                </div>
            @endforeach
        </div>
    @endforeach
</div>

@if (is_cms())
    <script type="text/javascript">

        function reloadPageContent() {
            var blockContent = $('.page-content');
            blockContent.html("");

            $.get('/cms/pages/{{ $page->id }}/content', function(content) {
                if (content.length == 0) {
                    blockContent.html('<div class="block" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>');
                }

                _.each(content, function(row) {
                    var elements = $('<div class="row"></div>');
                    _.each(row, function (item) {
                        var newRow = (item['first']) ? "clear" : "";
                        var element = '<div class="block columns medium-'+item['width']+' medium-offset-'+item['offset']+' '+newRow+'" data-name="'+item['name']+'" data-gs-x="'+item['x']+'" data-gs-y="'+item['y']+'" data-gs-width="'+item['width']+'" data-gs-height="'+item['height']+'" data-module="'+item['module']+'" data-editable>'+item['content']+'</div>';

                        elements.append(element);
                    });
                    blockContent.append(elements);
                });
            });
        }

        function addPagesToEditor (ContentTools)  {
            var _linkDialogMount = ContentTools.LinkDialog.prototype.mount;
            ContentTools.LinkDialog.prototype.mount = function() {
                // Call original behaviour
                _linkDialogMount.apply(this);

                // Add the auto-complete to the link input (we provide a static list but most likely you'd
                // have the auto-complete call the server for a list).
                new Awesomplete(this._domInput, {
                    list: {!! $pages !!}
                });
            };
        }


    </script>
@endif