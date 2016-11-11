@extends('templates.admin.modals.modal', ['target'=>'selectModuleModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer module</strong></h4>
@overwrite

@section('modal-body')
    <select-module></select-module>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')
    <script type="text/javascript">

    </script>
@overwrite