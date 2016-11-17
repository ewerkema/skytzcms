<div class="news-wrapper">
    <div id="news-{{ $id }}" class="portlet">
        <h1>Album: {{ Album::find($id)->name }}</h1>
        @foreach (Album::find($id)->media()->get() as $image)
            <a href="{{ $image->photo_url('original') }}" target="_blank" class="group1">
                <img src="{{ $image->photo_url('thumbnail') }}" alt="{{ $image->description }}">
            </a>
        @endforeach
    </div>
</div>