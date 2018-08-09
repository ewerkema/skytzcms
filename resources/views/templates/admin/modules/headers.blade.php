@if ($header = Header::find($id))
    @if ($header->image_id)
        <div class="image header-image"><img src="/{{ Media::find($header->image_id)->path }}" alt=""></div>
    @elseif ($header->slider_id)
        @include('templates.admin.modules.sliders', ['id' => $header->slider_id])
    @endif
@else
    <p>Deze header bestaat niet meer / is verwijderd.</p>
@endif