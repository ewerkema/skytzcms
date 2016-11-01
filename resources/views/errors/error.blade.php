@extends('templates.admin.main', [
    'template' => 'templates.admin.empty',
    'menu' => Page::all()->where('menu', 1),
    'nonmenu' => Page::all()->where('menu', 0)
])

@section('content')
    <div class="error-container">
        <div class="error-content">
            <div class="error-title">{{ $title }}</div>
        </div>
    </div>
@stop