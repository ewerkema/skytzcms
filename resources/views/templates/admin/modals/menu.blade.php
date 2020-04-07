@extends('templates.admin.modals.modal', ['target' => 'menuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Menu instellingen</strong></h4>
@overwrite

@section('modal-body')
    <menu-manager target="#menuModal"></menu-manager>
@overwrite

@section('modal-footer')

@overwrite