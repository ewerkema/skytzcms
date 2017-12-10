<div class="portlet">
    <h1>{{ $project->title }}</h1>
    <p><i>Gepubliceerd: {{ $project->created_at->diffForHumans() }} ({{ $project->created_at->toDateString() }})</i></p>
    @if (sizeof($project->images) > 0)
        <div class="project-images">
            @foreach($project->images as $image)
                <a class="project-image project{{ $project->id }}" href="{{ $image->image->photo_url('original') }}" target="_blank">
                    <img src="/{{ $image->image->path }}" alt="">
                </a>
            @endforeach
        </div>
    @endif
    {!! $project->body !!}
    <button href="#" onclick="window.history.back();">Ga terug</button>
</div>

<script>
    $(document).ready(function(){
        $(".project{{ $project->id }}").colorbox({rel:'project{{ $project->id }}', maxWidth:'50%', fixed: true});
    });
</script>