@extends('templates.admin.guest')

@section('head')
    @parent
    <title>{{ $title }}</title>

    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <link href="/images/favicon.png" rel="shortcut icon" />
@stop

@section('header_cms')
    <div id="cms">
        @include('templates.admin.partials.header')
    </div>
@stop

@section('content')
    <div class="error-container">
        <div class="error-content">
            <div class="error-title">{{ $title }}</div>
        </div>
    </div>
@stop

@section('bottom')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
@stop