@extends('templates.admin.modals.modal', ['target' => 'usersModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Gebruikersbeheer</strong></h4>
@overwrite

@section('modal-body')
    <list-users target="#usersModal"></list-users>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite