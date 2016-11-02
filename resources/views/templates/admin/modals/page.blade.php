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
        {!! Form::label('slug', 'Pagina link', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon" id="page-url">{{ url("/ ") }}</span>
                {!! Form::text('slug', ($currentPage->slug=="index") ? "" : $currentPage->slug, array('class'=>'form-control', 'autofocus', 'aria-describedby' => 'page-url')) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            {!! Form::text('meta_title', $currentPage->meta_title,array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-8">
            {!! Form::textarea('meta_desc',$currentPage->meta_desc,array('class'=>'form-control','placeholder' => 'Pagina beschrijving', 'autofocus')) !!}
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

        request.addField('title');
        request.addField('slug', 'text', 'index');
        request.addField('meta_title');
        request.addField('meta_desc');
        request.addField('menu', 'checkbox');

        request.onSubmit(function(data) {
            if (data['slug'] === undefined) {
                this.form.find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
            }

            window.location.href = '{{ cms_url("/") }}/'+data['slug'];
        });
    </script>
@overwrite