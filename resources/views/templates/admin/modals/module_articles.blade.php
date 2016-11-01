@extends('templates.admin.modals.modal', ['target'=>'moduleArticlesModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module nieuws</strong></h4>
@overwrite

@section('modal-body')
    <list-articles></list-articles>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite