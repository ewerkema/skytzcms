@extends('templates.admin.modals.modal', ['target' => 'addLinkToMenuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Losse link toevoegen aan menu</strong></h4>
@overwrite

@section('modal-body')
    <add-link-to-menu target="#addLinkToMenuModal" url="{{ url("/ ") }}"></add-link-to-menu>
@overwrite

@section('modal-footer')

@overwrite