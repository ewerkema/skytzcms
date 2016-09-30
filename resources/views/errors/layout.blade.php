@extends('templates.admin.app', ['template' => 'templates.admin.guest'])

@section('content')
    <div class="error-container">
        <div class="error-content">
            <div class="error-title">{{ $title }}</div>
        </div>
    </div>
@stop
