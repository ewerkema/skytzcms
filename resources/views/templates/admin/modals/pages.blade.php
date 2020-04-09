@extends('templates.admin.modals.modal', ['target' => 'pageManagerModal', 'fullscreen' => true])

@section('modal-header')
    <h4 class="modal-title"><strong>Pagina beheer</strong></h4>
@overwrite

@section('modal-body')
    <page-manager target="#pageManagerModal" url="{{ url("/ ") }}"></page-manager>
@overwrite

@section('modal-footer')

@overwrite