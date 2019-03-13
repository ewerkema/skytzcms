@extends('templates.admin.modals.modal', ['target' => 'selectMediaWithEditModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer afbeelding</strong></h4>
@overwrite

@section('modal-body')
    <insert-image :enable-edit="true" target="#selectMediaWithEditModal"></insert-image>
@overwrite

@section('modal-footer')

@overwrite