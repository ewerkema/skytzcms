@extends('templates.admin.modals.modal', ['target' => 'addPageToMenuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Pagina toevoegen aan menu</strong></h4>
@overwrite

@section('modal-body')
    <add-page-to-menu target="#addPageToMenuModal" url="{{ url("/ ") }}"></add-page-to-menu>
@overwrite

@section('modal-footer')

@overwrite