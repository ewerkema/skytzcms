
<div class="page-content" data-page="{{ $currentPage->id }}">
    @if (sizeof($currentPage->content) == 0)
        <div class="block" data-name="block0" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>
    @endif

    @foreach ($currentPage->getContent() as $row)
        <div class="row">
            @foreach ($row as $block)
                <div class="block columns medium-{{ $block['width'] }} medium-offset-{{ $block['offset'] }}" data-name="{{ $block['name'] }}" data-gs-x="{{ $block['x'] }}" data-gs-y="{{ $block['y'] }}" data-gs-width="{{ $block['width'] }}" data-gs-height="{{ $block['height'] }}" data-module="{{ isset($block['module']) ? $block['module'] : 0 }}" data-module-id="{{ isset($block['module_id']) ? $block['module_id'] : 0 }}" data-editable>
                    {!! $block['content'] !!}
                </div>
            @endforeach
        </div>
    @endforeach
</div>

@if (!Auth::guest())
    <script type="text/javascript">

        function publishWebsite() {

            swal({
                title: "Website publiceren?",
                text: "Alle gemaakte wijzigingen zullen zichtbaar worden op de website.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2ab27b",
                confirmButtonText: "Ja, website publiceren",
            }).then(function(){
                var request = new Request('{{ cms_url('pages/publish') }}');
                request.setType('POST');
                request.send(function() {
                    swal({
                        title: 'Website gepubliceerd!',
                        text: 'Alle wijzigingen zijn succesvol online gezet.',
                        type: 'success',
                        timer: 2000
                    });
                });
            });
        }

        function reloadPageContent() {
            var blockContent = $('.page-content');
            blockContent.html("");

            $.get('/cms/pages/{{ $currentPage->id }}/content', function(content) {
                if (content.length == 0) {
                    blockContent.html('<div class="block" data-name="block0" data-gs-x="0" data-gs-y="0" data-gs-width="12" data-gs-height="2" data-module="0" data-editable></div>');
                }

                _.each(content, function(row) {
                    var elements = $('<div class="row"></div>');
                    _.each(row, function (item) {
                        var newRow = (item['first']) ? "clear" : "";
                        var module = (item['module'] === undefined) ? 0 : item['module'];
                        var module_id = (item['module_id'] === undefined) ? 0 : item['module_id'];
                        var element = '<div class="block columns medium-'+item['width']+' medium-offset-'+item['offset']+' '+newRow+'" data-name="'+item['name']+'" data-gs-x="'+item['x']+'" data-gs-y="'+item['y']+'" data-gs-width="'+item['width']+'" data-gs-height="'+item['height']+'" data-module="'+module+'" data-module-id="'+module_id+'" data-editable>'+item['content']+'</div>';

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
                    list: {!! Page::getEditorLinks() !!}
                });
            };
        }


    </script>
@endif