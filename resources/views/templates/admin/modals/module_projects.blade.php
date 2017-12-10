@extends('templates.admin.modals.modal', ['target'=>'moduleProjectsModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module projecten</strong></h4>
@overwrite

@section('modal-body')
    <list-projects></list-projects>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite