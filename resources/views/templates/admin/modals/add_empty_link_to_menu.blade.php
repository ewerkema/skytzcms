@extends('templates.admin.modals.modal', ['target' => 'addEmptyLinkToMenuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Losse link toevoegen aan menu</strong></h4>
@overwrite

@section('modal-body')
    <add-link-to-menu target="#addEmptyLinkToMenuModal" url="{{ url("/ ") }}" :empty="true"></add-link-to-menu>
@overwrite

@section('modal-footer')

@overwrite