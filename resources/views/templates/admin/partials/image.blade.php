@if (isset($image_id))
    @php($media = Media::find($image_id))
    @if ($media != null && file_exists($media->path))
        <img src="/{{ $media->path }}" alt="{{ $media->description }}">
    @else
        <img src="{{ asset('afbeelding_niet_gevonden.png') }}" alt="Afbeelding niet gevonden">
    @endif
@endif