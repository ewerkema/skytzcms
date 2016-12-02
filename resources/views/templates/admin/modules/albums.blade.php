<div class="album-wrapper">
    <div id="album-{{ $id }}" class="portlet album">
        @foreach (Album::find($id)->media()->get() as $image)
            <a href="{{ $image->photo_url('original') }}" target="_blank" class="group1 image">
                <img src="{{ $image->photo_url('thumbnail') }}" alt="{{ $image->description }}">
            </a>
        @endforeach
    </div>
</div>