 @forelse($rows as $key => $r)
    <div class="col-xs-6 col-md-3">
        <div class="media-image-gallery">
            <a href="#" class="thumbnail">
            @if($r->extension!='docx' && $r->extension!='pdf' && $r->extension!='doc')
                <img src="{{$r->photo_url('thumbnail')}}">
                <p>{{$r->name}}</p>
            @else
                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU3Y2U1YWRhN2YgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTdjZTVhZGE3ZiI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI2MSIgeT0iOTQuNSI+MTcxeDE4MDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" alt="...">
                <p>{{$r->name}}</p>
            @endif
            </a>
            <div class="trash">
                <a href="javascript:;"><span class="glyphicon glyphicon-trash remove-media" data-id='{{$r->id}}'></span></a>
            </div>
        </div>
    </div>
@empty
    <p class='flex-center'>Geen resultaten gevonden.</p>
@endforelse
<div class="clear"></div>

<nav class="flex-center" aria-label="Media navigation">
    <div class="media-pagination">
        {{ $rows->links() }}
    </div>
</nav>
<script type="text/javascript">
    $(document).ready(function(){
        $('.pagination a').click(function(e){
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];

            getMedia(page);
        });

        function getMedia(page){
            $.get('/cms/media?page='+page, function (data) {
                $('.bootstrap-row').html(data);
                $('#spinner').hide();
            });
        }
        $('.remove-media').click(function(e){
            e.preventDefault();
            var del_id = $(this).data('id');
            var url = '/cms/media/'+del_id;

            swal({
                title: 'Weet je het zeker?',
                text: "Deze wijzigingen kunnen niet meer ongedaan worden!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ja, verwijder dit bestand!'
            }).then(function() {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function( data ) {
                        if(data.status == 'success') {
                            $('#spinner').hide();
                            getMedia(1);
                        }
                    }
                });
                swal(
                    'Verwijderd!',
                    'Het bestand is verwijderd.',
                    'success'
                );
            });
        });
    });

</script>