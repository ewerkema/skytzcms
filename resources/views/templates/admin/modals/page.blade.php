@extends('templates.admin.modals.modal', ['target'=>'pageModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Pagina instellingen:</strong> {{ $currentPage->meta_title }}</h4>
@overwrite

@section('modal-body')
    {!! Form::open(array('id' => 'pageForm', 'class' => 'form-horizontal')) !!}

    <div class="alert form-message" role="alert" style="display: none;"></div>
    <div class="form-group">
        {!! Form::label('title', 'Pagina naam', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            {!! Form::text('title',$currentPage->title, array('class'=>'form-control','placeholder' => 'Pagina naam', 'required', 'autofocus')) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="header_image" class="col-md-3 control-label">Pagina header</label>

        <div class="col-md-8">
            <div class="input-group input-pointer">
                <input type="hidden" name="header_image_id" value="{{ $currentPage->header_image_id }}" class="form-control selected_media_id" />
                <span class="input-group-addon" id="media-picture" onclick="selectMedia()"><span class="glyphicon glyphicon-picture"></span></span>
                <input type="text" name="header_image_name" onclick="selectMedia()" value="{{ ($currentPage->header_image_id) ? Media::find($currentPage->header_image_id)->name : "" }}" class="form-control selected_media_name" placeholder="Pagina header" autofocus />
                <div class="input-group-btn">
                    <button class="btn btn-default removeMedia" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('slug', 'Pagina link', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon" id="page-url">{{ str_replace($currentPage->slug, "", url($currentPage->getSlug())) }}</span>
                {!! Form::text('slug', ($currentPage->slug=="index") ? "" : $currentPage->slug, array('class'=>'form-control', 'autofocus', 'aria-describedby' => 'page-url')) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            {!! Form::text('meta_title', $currentPage->meta_title, array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            {!! Form::textarea('meta_desc',$currentPage->meta_desc, array('class'=>'form-control','placeholder' => 'Pagina beschrijving', 'autofocus')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('menu', 'Weergeven in menu', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            <label class="Switch">
                {!! Form::checkbox('menu', 'menu', $currentPage->menu) !!}
                <div class="Switch__slider"></div>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="parent_id" class="col-md-3 control-label">Weergeven in submenu van</label>

        <div class="col-md-8">
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="" {{ (!$currentPage->parent_id) ? "selected" : "" }}>Geen submenu</option>
                @foreach (Page::getMenuWithoutSubpages() as $page)
                    <option value="{{ $page->id }}" {{ ($page->id == $currentPage->parent_id) ? "selected" : "" }}>
                        {{ $page->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {!! Form::close() !!}
@overwrite

@section('modal-footer')
    <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
@overwrite

@section('javascript')
    <script type="text/javascript">
        var request = new Request('{{ cms_url('pages/'.$currentPage->id) }}');
        request.setType('PATCH');
        request.setForm('#pageForm');

        request.addFields(['title', 'meta_title', 'meta_desc', 'parent_id', 'header_image_id']);
        request.addCheckboxes(['menu']);
        request.addField('slug', 'text', 'index');

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

            if (value) {
                visibleInMenu.prop('checked', true);
            }
        });

        visibleInMenu.change(function() {
            var checked = $(this).is(":checked");

            if (!checked)
                subpageSelect.val(subpageSelect.find('option:first').val());
        })
    </script>
@overwrite