@extends('templates.admin.modals.modal', ['target' => 'moduleHtmlBlocksModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module HTML Blokken</strong></h4>
@overwrite

@section('modal-body')
    <list-html-blocks target="#moduleHtmlBlocksModal"></list-html-blocks>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite