<div class="modal fade" tabindex="-1" role="dialog" id="mediaModal" aria-labelledby="mediaModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Media overzicht</strong></h4>
            </div>
            <div class="modal-body">
                <div class="bootstrap-row">
                    @for ($i = 0; $i < 12; $i++)
                        <div class="col-xs-6 col-md-3">
                            <a href="#" class="thumbnail">
                                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU3Y2U1YWRhN2YgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTdjZTVhZGE3ZiI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI2MSIgeT0iOTQuNSI+MTcxeDE4MDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" alt="...">
                                <p>Nummer: {{ $i }}</p>
                            </a>
                        </div>
                    @endfor

                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-lg">
                            <li class="disabled">
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="submit" form="mediaForm" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        var form = $('#mediaForm');
        form.submit(function(){
            $.ajax({
                url: '{{ cms_url('media') }}',
                type: 'POST',
                data: {

                },
                success: function(data) {
                    if (data['slug'] === undefined) {
                        form.find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                        return;
                    }

                    window.location.href = '{{ cms_url("/") }}/'+data['slug'];
                },
                error: function(data){
                    var errors = data.responseJSON;
                    var errorMessage = "";

                    if (errors === undefined)
                        errorMessage += "Er ging iets fout, we zullen er zo spoedig mogelijk naar kijken.";

                    for (var error in errors) {
                        errorMessage += errors[error]+"<br />";
                    }

                    form.find('.form-message').addClass("alert-danger").html(errorMessage).show();
                }
            });

            return false;
        });
    });
</script>
