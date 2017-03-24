<div class="news-wrapper">
    @if ($articleGroup = ArticleGroup::find($id))
        <div id="news-{{ $id }}" class="news">
            @foreach (ArticleGroup::find($id)->articles()->get() as $article)
                @if ($article->image_id)
                    @if (strlen($article->summary) > 0)
                        <div class="portlet">
                            <div class="image small-3">
                                <a href="{{ $article->getLink() }}"><img src="/{{ Media::find($article->image_id)->path }}" alt=""></a>
                            </div>
                            <div class="text small-9">
                                <h3 class="newsheader">{{ $article->title }}</h3>
                                <p>{{ $article->summary }}</p>
                                <a href="{{ $article->getLink() }}">Lees verder</a>
                                <hr>
                            </div>
                            <div class="clear"></div>
                        </div>
                    @else
                        <div class="image small-4">
                            <a href="{{ $article->getLink() }}"><img src="/{{ Media::find($article->image_id)->path }}" alt=""></a>
                            <h3 class="newsheader block"><a href="{{ $article->getLink() }}">{{ $article->title }}</a></h3>
                        </div>
                    @endif
                @else
                    <div class="portlet">
                        <h3 class="newsheader">{{ $article->title }}</h3>
                        <p>{{ $article->summary }}</p>
                        <a href="{{ $article->getLink() }}">Lees verder</a>
                        <hr>
                    </div>
                @endif

            @endforeach
        </div>
    @else
        <p>Dit nieuwsoverzicht bestaat niet meer / is verwijderd.</p>
    @endif
</div>