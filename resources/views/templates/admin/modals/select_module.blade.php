@extends('templates.admin.modals.modal', ['target' => 'selectModuleModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer module</strong></h4>
@overwrite

@section('modal-body')
    <select-module target="#selectModuleModal"></select-module>
@overwrite

@section('modal-footer')

@overwrite
