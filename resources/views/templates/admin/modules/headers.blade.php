@if ($header = Header::find($id))
    @if ($header->image_id)
        <div class="image header-image">
            @include('templates.admin.partials.image', ['image_id' => $header->image_id])
            @if ($header->content)
                <div class="text {{ $header->getPosition() }}">
                    <div class="inner">{!! $header->content !!}</div>
                </div>
            @endif

            @if ($header->link())
                <div class="link"><a href="{{ $header->link() }}"{{ $header->open_in_new_tab ? " target='_BLANK'" : '' }}></a></div>
            @endif
        </div>
    @elseif ($header->slider_id)
        @include('templates.admin.modules.sliders', ['id' => $header->slider_id])
    @elseif ($header->video)
        <iframe width="{{ config('skytz.header_width') }}" height="{{ config('skytz.header_height') }}" src="{{ $header->video }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    @endif
@else
    <p>Deze header bestaat niet meer / is verwijderd.</p>
@endif