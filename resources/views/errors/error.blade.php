@extends('templates.admin.main', ['template' => 'templates.admin.empty'])

@section('content')
    <div class="error-container">
        <div class="error-content">
            <div class="error-title">{{ $title }}</div>
        </div>
    </div>
@stop