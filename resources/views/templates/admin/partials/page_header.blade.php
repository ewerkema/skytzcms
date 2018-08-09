@php($header_id = Setting::get('header_id'))

@if ($currentPage->header != NULL)
    @include('templates.admin.modules.headers', ['id' => $currentPage->header_id])
@elseif ($header_id != 0)
    @include('templates.admin.modules.headers', ['id' => $header_id])
@endif