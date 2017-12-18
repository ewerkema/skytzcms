@extends('templates.admin.modals.modal', ['target'=>'selectMediaModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Selecteer afbeelding</strong></h4>
@overwrite

@section('modal-body')
    <select-media></select-media>
@overwrite

@section('modal-footer')

@overwrite