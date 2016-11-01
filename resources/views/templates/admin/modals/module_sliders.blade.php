@extends('templates.admin.modals.modal', ['target'=>'moduleSlidersModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module sliders</strong></h4>
@overwrite

@section('modal-body')
    <list-sliders></list-sliders>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite