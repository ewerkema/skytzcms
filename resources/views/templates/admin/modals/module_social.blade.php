@extends('templates.admin.modals.modal', ['target' => 'moduleSocialModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module Social Media</strong></h4>
@overwrite

@section('modal-body')
    <list-social :social-media-types="{{ json_encode(Social::getTypes()) }}" target="#moduleSocialModal"></list-social>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite