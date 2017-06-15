<div class="album-wrapper">
    <div id="album-{{ $id }}" class="portlet album">
        @if ($album = Album::find($id))
            @foreach ($album->getOrderedMedia() as $image)
                <a href="{{ $image->photo_url('original') }}" target="_blank" class="group{{ $id }} image">
                    <img src="{{ $image->photo_url('thumbnail') }}" alt="{{ $image->description }}">
                </a>
            @endforeach
        @else
            <p>Dit foto album bestaat niet meer / is verwijderd.</p>
        @endif
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".group{{ $id }}").colorbox({rel:'group{{ $id }}', maxWidth:'50%', fixed: true});
    });
</script>