@extends('templates.admin.modals.modal', ['target' => 'selectMediaModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer afbeelding</strong></h4>
@overwrite

@section('modal-body')
    <insert-image target="#selectMediaModal"></insert-image>
@overwrite

@section('modal-footer')

@overwrite