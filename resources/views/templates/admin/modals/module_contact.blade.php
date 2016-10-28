@extends('templates.admin.modals.modal', ['target'=>'moduleContactModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module contact: instellingen</strong></h4>
@overwrite

@section('modal-body')
    <form action="#" class="form-horizontal" id="moduleContactForm">
        <div class="alert form-message" role="alert" style="display: none;"></div>
        <div class="form-group">
            <label for="contact_email" class="col-md-3 control-label">Contact e-mailadres</label>

            <div class="col-md-8">
                <input type="text" name="contact_email" value="{{ $settings['contact_email']->value }}" class="form-control" placeholder="Contact email" autofocus />
            </div>
        </div>
        <div class="form-group">
            <label for="contact_success_message" class="col-md-3 control-label">Bedankt bericht</label>

            <div class="col-md-8">
                <textarea type="text" name="contact_success_message" class="form-control" placeholder="Bedankt bericht" autofocus>{{ $settings['contact_success_message']->value }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="contact_name_visible" class="col-md-3 control-label">Naam veld weergeven</label>

            <div class="col-md-8">
                <label class="Switch">
                    {!! Form::checkbox('contact_name_visible', 'contact_name_visible', $settings['contact_name_visible']->value) !!}
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="contact_subject_visible" class="col-md-3 control-label">Onderwerp veld weergeven</label>

            <div class="col-md-8">
                <label class="Switch">
                    {!! Form::checkbox('contact_subject_visible', 'contact_subject_visible', $settings['contact_subject_visible']->value) !!}
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="contact_telephone_visible" class="col-md-3 control-label">Telefoonnummer veld weergeven</label>

            <div class="col-md-8">
                <label class="Switch">
                    {!! Form::checkbox('contact_telephone_visible', 'contact_telephone_visible', $settings['contact_telephone_visible']->value) !!}
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="contact_message_visible" class="col-md-3 control-label">Bericht veld weergeven</label>

            <div class="col-md-8">
                <label class="Switch">
                    {!! Form::checkbox('contact_message_visible', 'contact_message_visible', $settings['contact_message_visible']->value) !!}
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>
    </form>
@overwrite

@section('modal-footer')
    <button type="submit" form="moduleContactForm" class="btn btn-primary">Opslaan</button>
@overwrite

@section('javascript')
    <script type="text/javascript">
        var request = new Request('{{ cms_url('settings') }}');
        request.setType('PATCH');
        request.setForm('#moduleContactForm');

        request.addFields(['contact_email', 'contact_success_message']);
        request.addCheckboxes(['contact_subject_visible', 'contact_name_visible', 'contact_telephone_visible', 'contact_message_visible']);

        request.onSubmit(function(data) {
            console.log(data);
            $('#moduleContactModal').modal('toggle');
            swal({
                title: 'Success!',
                text: 'Contact instellingen zijn succesvol aangepast.',
                type: "success",
                timer: 2000
            });
        });
    </script>
@overwrite