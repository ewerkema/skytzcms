@extends('templates.admin.modals.modal', ['target' => 'headersModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Header instellingen</strong></h4>
@overwrite

@section('modal-body')
    <list-headers target="#headersModal"></list-headers>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite