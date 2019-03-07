<div class="album-wrapper album album-overview">
    @foreach (Album::latest()->get() as $album)
        <div id="album-{{ $album->id }}" class="portlet">
            @php ($image = $album->getOrderedMedia()->first() )

            <a href="{{ $album->getLink() }}" class="image">
                @if ($image != null)
                    <img src="{{ $image->photo_url('thumbnail') }}" alt="{{ $image->description }}">
                @else
                    <img src="{{ asset('afbeelding_niet_gevonden.png') }}" alt="{{ $album->name }}">
                @endif
                <p>{{ $album->name }}</p>
            </a>
        </div>
    @endforeach
</div>