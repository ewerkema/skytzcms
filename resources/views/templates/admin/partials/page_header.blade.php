@if ($currentPage->header != NULL)
    <div class="image header-image"><img src="/{{ $currentPage->header->path }}" alt=""></div>
@elseif (Setting::get('header_image'))
    <div class="image header-image"><img src="/{{ Media::find(Setting::get('header_image'))->path }}" alt=""></div>
@elseif (Setting::get('header_slider'))
    @include('templates.admin.modules.sliders', ['id' => Setting::get('header_slider')])
@endif