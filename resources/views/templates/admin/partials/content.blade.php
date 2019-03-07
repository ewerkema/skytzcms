@if (is_cms())
    @include('templates.admin.partials.backend_content')
@else
    @include('templates.admin.partials.published_content')
@endif

<script type="text/javascript">
    let popupImages = "img[openinpopup='true']";

    function enableImagePopup() {
        $(popupImages).each(function(){
            let anchor = $('<a/>').attr({'href': this.src}).colorbox({rel: 'group', maxWidth:'50%', maxHeight:'100%', fixed: true});
            $(this).wrap(anchor);
        });
    }

    function disableImagePopup() {
        $(popupImages).each(function() {
            let parent = $(this).parent();
            parent.replaceWith($(this));
        });
    }

    $(document).ready(function(){
        enableImagePopup();
    });
</script>