@extends('templates.admin.modals.modal', ['target'=>'selectMediaWithEditModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer afbeelding</strong></h4>
@overwrite

@section('modal-body')
    <select-media-with-edit></select-media-with-edit>
@overwrite

@section('modal-footer')

@overwrite