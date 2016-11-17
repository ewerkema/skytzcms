<div class="news-wrapper">
    @if (isset($_GET['article']))
        @php ($article = Article::find($_GET['article']))
        <div class="portlet">
            <h1>{{ $article->title }}</h1>
            <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
            {!! $article->body !!}
            <button href="#" onclick="window.history.back();">Ga terug</button>
            <hr>
        </div>
    @else
        <div id="news-{{ $id }}">
            <h1>Nieuws: {{ ArticleGroup::find($id)->title }}</h1>
            @foreach (ArticleGroup::find($id)->articles()->get() as $article)
                <div class="portlet">
                    <h3 class="newsheader">{{ $article->title }}</h3>
                    <p><i>Gepubliceerd: {{ $article->created_at->diffForHumans() }} ({{ $article->created_at->toDateString() }})</i></p>
                    <p>{{ $article->summary }}</p>
                    <a href="?article={{ $article->id }}">Lees verder</a>
                    <hr>
                </div>
            @endforeach
        </div>
    @endif
</div>