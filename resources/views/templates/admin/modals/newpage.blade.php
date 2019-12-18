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