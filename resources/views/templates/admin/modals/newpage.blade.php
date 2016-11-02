@extends('templates.admin.modals.modal', ['target'=>'newPageModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Nieuwe pagina aanmaken</strong></h4>
@overwrite

@section('modal-body')
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
        <div class="form-group">
            <label for="parent_id" class="col-md-3 control-label">Weergeven in submenu van</label>

            <div class="col-md-8">
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="" selected>Geen submenu</option>
                    @foreach (Page::getMenuWithoutSubpages() as $page)
                        <option value="{{ $page->id }}">
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
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

        request.addFields(['title', 'meta_title', 'meta_desc', 'parent_id']);
        request.addField('slug', 'text', 'index');
        request.addField('menu', 'checkbox');

        request.onSubmit(function(data) {
            if (data['redirectTo'] === undefined) {
                request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                return;
            }

            window.location.href = '{{ cms_url("/") }}/'+data['redirectTo'];
        });

        var subpageSelect = $('[name=parent_id]');
        var visibleInMenu = $('[name=menu]');

        subpageSelect.change(function() {
            var value = $(this).val();

            if (value)
                visibleInMenu.prop('checked', true);
        });

        visibleInMenu.change(function() {
            var checked = $(this).is(":checked");

            if (!checked)
                subpageSelect.val(subpageSelect.find('option:first').val());
        })
    </script>
@overwrite