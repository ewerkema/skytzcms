@extends('templates.admin.modals.modal', ['target'=>'newPageModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Nieuwe pagina aanmaken</strong></h4>
@overwrite

@section('modal-body')
    <form action="#" class="form-horizontal" id="newPageForm">
        <div class="alert form-message" role="alert" style="display: none;"></div>
        <div class="form-group">
            <label for="newPageTitle" class="col-md-3 control-label">Pagina naam</label>

            <div class="col-md-8">
                <input type="text" name="title" class="form-control" placeholder="Pagina naam" id="newPageTitle" required autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="header_id3" class="col-md-3 control-label">Pagina header</label>

            <div class="col-md-8">
                <select class="form-control" id="header_id3" name="header_id">
                    <option value="0">Geen header</option>
                    @foreach (Header::all() as $header)
                        <option value="{{ $header->id }}" {{ ($header->id == $currentPage->header_id) ? "selected" : "" }}>
                            {{ $header->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="newPageSlug" class="col-md-3 control-label">Pagina link</label>

            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon" id="page-url">{{ url("/ ") }}</span>
                    <input type="text" name="slug" class="form-control" id="newPageSlug" aria-describedby="page-url" autofocus />
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
    </form>
@overwrite

@section('modal-footer')
    <button type="submit" form="newPageForm" class="btn btn-primary">Opslaan</button>
@overwrite

@section('javascript')
    <script type="text/javascript">
        var request = new Request('{{ cms_url('pages') }}');
        request.setType('POST');
        request.setForm('#newPageForm');

        request.addFields(['title', 'meta_title', 'meta_desc', 'header_id']);
        request.addField('slug', 'text', 'index');

        request.onSubmit(function(data) {
            if (data['redirectTo'] === undefined) {
                request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                return;
            }

            window.location.href = '{{ cms_url("/") }}/'+data['redirectTo'];
        });


        let slugActive = true;
        $('#newPageForm #newPageTitle').on('input', function() {
            if (slugActive) {
                $('#newPageForm #newPageSlug').val(toSlug($(this).val()));
            }
        });

        $('#newPageForm #newPageSlug').keypress(function() {
            slugActive = false;
        });

        function toSlug(str) {
            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            let from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
            let to   = "aaaaaeeeeeiiiiooooouuuunc------";
            for (let i=0, l=from.length ; i<l ; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            return str;
        }
    </script>
@overwrite