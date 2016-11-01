@extends('templates.admin.modals.modal', ['target'=>'moduleAlbumsModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module albums</strong></h4>
@overwrite

@section('modal-body')
    <list-albums></list-albums>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite