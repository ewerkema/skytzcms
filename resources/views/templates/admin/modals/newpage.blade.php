<div class="modal fade" tabindex="-1" role="dialog" id="newPageModal" aria-labelledby="newPageModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Nieuwe pagina aanmaken</strong></h4>
            </div>
            <div class="modal-body">
                <form action="#" class="form-horizontal" id="newPageForm">

                    <div class="alert form-message" role="alert" style="display: none;"></div>
                    <div class="form-group">
                        <label for="title" class="col-md-3 control-label">Pagina naam</label>

                        <div class="col-md-8">
                            <input type="text" name="title" class="form-control" placeholder="Pagina naam" required autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slug" class="col-md-3 control-label">Pagina link</label>

                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon" id="page-url">{{ url("/ ") }}</span>
                                <input type="text" name="slug" class="form-control" aria-describedby="page-url" required autofocus />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meta_title" class="col-md-3 control-label">Pagina titel</label>

                        <div class="col-md-8">
                            <input type="text" name="meta_title" class="form-control" placeholder="Pagina titel" required autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meta_desc" class="col-md-3 control-label">Pagina beschrijving</label>

                        <div class="col-md-8">
                            <textarea name="meta_desc" class="form-control" placeholder="Pagina beschrijving" autofocus></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="menu" class="col-md-3 control-label">Weergeven in menu</label>

                        <div class="col-md-8">
                            <label class="Switch">
                                <input type="checkbox" name="menu">
                                <div class="Switch__slider"></div>
                            </label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="submit" form="newPageForm" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        var form = $('#newPageForm');
        form.submit(function(){
            $.ajax({
                url: '{{ cms_url('pages') }}',
                type: 'POST',
                data: {
                    'title' : form.find('input[name=title]').val(),
                    'slug' : (form.find('input[name=slug]').val()) ? form.find('input[name=slug]').val() : "index",
                    'meta_title' : form.find('input[name=meta_title]').val(),
                    'meta_desc' : form.find('textarea[name=meta_desc]').val(),
                    'menu' : (form.find('input[name=menu]').is(":checked")) ? 1 : 0,
                },
                success: function(data) {
                    console.log(data);
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
