<div class="portlet">
    <h1>{{ $article->title }}</h1>
    <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
    @if ($article->image_id)
        <div class="image">
            <img src="/{{ Media::find($article->image_id)->path }}" alt="">
        </div>
    @endif
    {!! $article->body !!}
    <button href="#" onclick="window.history.back();">Ga terug</button>
</div>