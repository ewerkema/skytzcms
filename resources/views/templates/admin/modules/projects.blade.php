<div class="news-wrapper">
    @if ($projectGroup = ProjectGroup::find($id))
        <div id="news-{{ $id }}" class="news">
            @foreach (ProjectGroup::find($id)->projects()->get() as $project)
                @if ($project->images->count())
                    @if (strlen($project->summary) > 0)
                        <div class="portlet">
                            <div class="image small-3">
                                <a href="{{ $project->getLink() }}"><img src="/{{ Media::find($project->images[0]->image_id)->path }}" alt=""></a>
                            </div>
                            <div class="text small-9">
                                <h3 class="newsheader">{{ $project->title }}</h3>
                                <p>{{ $project->summary }}</p>
                                <a href="{{ $project->getLink() }}">Lees verder</a>
                                <hr>
                            </div>
                            <div class="clear"></div>
                        </div>
                    @else
                        <div class="image small-4">
                            <a href="{{ $project->getLink() }}"><img src="/{{ Media::find($project->images[0]->image_id)->path }}" alt=""></a>
                            <h3 class="newsheader block"><a href="{{ $project->getLink() }}">{{ $project->title }}</a></h3>
                        </div>
                    @endif
                @else
                    <div class="portlet">
                        <h3 class="newsheader">{{ $project->title }}</h3>
                        <p>{{ $project->summary }}</p>
                        <a href="{{ $project->getLink() }}">Lees verder</a>
                        <hr>
                    </div>
                @endif

            @endforeach
        </div>
    @else
        <p>Dit project overzicht bestaat niet meer / is verwijderd.</p>
    @endif
</div>