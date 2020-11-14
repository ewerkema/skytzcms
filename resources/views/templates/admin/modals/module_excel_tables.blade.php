@extends('templates.admin.modals.modal', ['target' => 'moduleExcelTablesModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Module Excel Tabellen</strong></h4>
@overwrite

@section('modal-body')
    <list-excel-tables target="#moduleExcelTablesModal"></list-excel-tables>
    <div class="clear"></div>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')

@overwrite