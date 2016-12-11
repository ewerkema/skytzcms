<div class="album-wrapper">
    <div id="album-{{ $id }}" class="portlet album">
        @if ($album = Album::find($id))
            @foreach ($album->media()->get() as $image)
                <a href="{{ $image->photo_url('original') }}" target="_blank" class="group1 image">
                    <img src="{{ $image->photo_url('thumbnail') }}" alt="{{ $image->description }}">
                </a>
            @endforeach
        @else
            <p>Dit foto album bestaat niet meer / is verwijderd.</p>
        @endif
    </div>
</div>