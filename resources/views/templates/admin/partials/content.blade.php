@if (is_cms())
    @include('templates.admin.partials.backend_content')
@else
    @include('templates.admin.partials.published_content')
@endif