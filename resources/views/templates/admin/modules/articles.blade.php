<div class="news-wrapper">
    @if ($articleGroup = ArticleGroup::find($id))
        <div id="news-{{ $id }}" class="news">
            <h1>Nieuws: {{ $articleGroup->title }}</h1>
            @foreach (ArticleGroup::find($id)->articles()->get() as $article)
                @if ($article->image_id)
                    <div class="portlet">
                        <div class="image small-3">
                            <a href="?article={{ $article->getSlug() }}"><img src="/{{ Media::find($article->image_id)->path }}" alt=""></a>
                        </div>
                        <div class="text small-9">
                            <h3 class="newsheader">{{ $article->title }}</h3>
                            <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
                            <p>{{ $article->summary }}</p>
                            <a href="?article={{ $article->getSlug() }}">Lees verder</a>
                            <hr>
                        </div>
                    </div>
                @else
                    <div class="portlet">
                        <h3 class="newsheader">{{ $article->title }}</h3>
                        <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
                        <p>{{ $article->summary }}</p>
                        <a href="?article={{ $article->getSlug() }}">Lees verder</a>
                        <hr>
                    </div>
                @endif

            @endforeach
        </div>
    @else
        <p>Dit nieuwsoverzicht bestaat niet meer / is verwijderd.</p>
    @endif
</div>