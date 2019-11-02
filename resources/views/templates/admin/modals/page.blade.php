@extends('templates.admin.modals.modal', ['target'=>'pageModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Pagina instellingen:</strong> {{ $currentPage->meta_title }}</h4>
@overwrite

@section('modal-body')
    {!! Form::open(array('id' => 'pageForm', 'class' => 'form-horizontal')) !!}

    <div class="alert form-message" role="alert" style="display: none;"></div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#mainPageTab" aria-controls="mainPageTab" role="tab" data-toggle="tab">Algemeen</a></li>
        <li role="presentation"><a href="#headerTab" aria-controls="headerTab" role="tab" data-toggle="tab">Header</a></li>
        <li role="presentation"><a href="#seoTab" aria-controls="seoTab" role="tab" data-toggle="tab">SEO</a></li>
    </ul>

    <div class="tab-content" id="pageTabs">
        <div role="tabpanel" class="tab-pane active" id="mainPageTab">
            <div class="form-group">
                {!! Form::label('title', 'Pagina naam', ['class' => 'col-md-3 control-label']) !!}

                <div class="col-md-8">
                    {!! Form::text('title',$currentPage->title, array('class'=>'form-control','placeholder' => 'Pagina naam', 'required')) !!}
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
        </div>
        <div role="tabpanel" class="tab-pane" id="seoTab">
            <div class="form-group">
                {!! Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']) !!}

                <div class="col-md-8">
                    {!! Form::text('meta_title', $currentPage->meta_title, array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('meta_keywords', 'Pagina zoekwoorden', ['class' => 'col-md-3 control-label']) !!}

                <div class="col-md-8">
                    {!! Form::text('meta_keywords', $currentPage->meta_keywords, array('class'=>'form-control', 'placeholder' => 'Pagina zoekwoorden')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']) !!}

                <div class="col-md-8">
                    {!! Form::textarea('meta_desc',$currentPage->meta_desc, array('class'=>'form-control','placeholder' => 'Pagina beschrijving')) !!}
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="headerTab">
            <div class="form-group">
                <label for="header_id" class="col-md-3 control-label">Pagina header</label>

                <div class="col-md-8">
                    <select class="form-control" id="header_id" name="header_id">
                        <option value="0">Geen header</option>
                        @foreach (Header::all() as $header)
                            <option value="{{ $header->id }}" {{ ($header->id == $currentPage->header_id) ? "selected" : "" }}>
                                {{ $header->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@overwrite

@section('modal-footer')
    <button id="deletePage" class="btn btn-danger left">Verwijder pagina</button>
    <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
@overwrite

@section('javascript')
    <script type="text/javascript">
        function selectMediaWithEdit() {
            $('#selectMediaWithEditModal').modal('toggle');
        }


        var request = new Request('{{ cms_url('pages/'.$currentPage->id) }}');
        request.setType('PATCH');
        request.setForm('#pageForm');

        request.addFields(['title', 'meta_title', 'meta_desc', 'meta_keywords', 'header_id']);
        request.addField('slug', 'text', 'index');

        request.onSubmit(function(data) {
            if (data['redirectTo'] === undefined) {
                request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                return;
            }

            window.location.href = '{{ cms_url("/") }}/'+data['redirectTo'];
        });

        $('#deletePage').click(function() {
            swal({
                title: "Weet je het zeker?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ja, verwijder deze pagina",
            }).then(function(){
                $.ajax({
                    url: '{{ cms_url('pages/'.$currentPage->id) }}',
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(data) {
                        if (data['redirectTo'] === undefined) {
                            request.getForm().find('.form-message').addClass("alert-danger").html("Er is iets onverwachts gebeurd, probeer het later opnieuw.").show();
                            return;
                        }

                        window.location.href = data['redirectTo'];
                    },
                    error: function (errorData) {
                        request.showError(errorData)
                    }
                });
            }).done();
        });
    </script>
@overwrite