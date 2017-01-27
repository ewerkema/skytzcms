<div class="news-wrapper">
    @if ($articleGroup = ArticleGroup::find($id))
        <div id="news-{{ $id }}" class="news">
            <h1>Nieuws: {{ $articleGroup->title }}</h1>
            @foreach (ArticleGroup::find($id)->articles()->get() as $article)
                <div class="portlet">
                    <h3 class="newsheader">{{ $article->title }}</h3>
                    <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
                    <p>{{ $article->summary }}</p>
                    <a href="?article={{ $article->getSlug() }}">Lees verder</a>
                    <hr>
                </div>
            @endforeach
        </div>
    @else
        <p>Dit nieuwsoverzicht bestaat niet meer / is verwijderd.</p>
    @endif
</div>