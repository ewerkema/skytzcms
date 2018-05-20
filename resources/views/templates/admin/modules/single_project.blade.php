<div class="portlet">
    <h1>{{ $project->title }}</h1>
    <p><i>Gepubliceerd: {{ $project->created_at->diffForHumans() }} ({{ $project->created_at->toDateString() }})</i></p>
    @if (sizeof($project->images) > 0)
        <div class="slider-wrapper">
            <div id="slider-project-{{ $project->id }}" class="slider">
                @foreach($project->images as $image)
                    @if ($image->image != null)
                        <div class="slide slide{{ $image->image->id }}"><img src="{{ $image->image->photo_url('original') }}" alt="{{ $image->image->description }}" /></div>
                    @endif
                @endforeach
            </div>
            <div id="slider-direction-nav-project-{{ $project->id }}" class="slider-direction-nav"></div>
            <div id="slider-control-nav-project-{{ $project->id }}" class="slider-control-nav"></div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                let slider = $('#slider-project-{{ $project->id }}').leanSlider({
                    directionNav: '#slider-direction-nav-project-{{ $project->id }}',
                    controlNav: '#slider-control-nav-project-{{ $project->id }}'
                });
            });
        </script>
    @endif
    <div class="row">
        <div class="large-6 small-12 left">
            <div id="projectMap" class="flex-video"></div>

            <script>
                let map;
                function initMap() {
                    let mapElement = document.getElementById('projectMap');
                    if (mapElement !== null)
                        map = new google.maps.Map(mapElement, {
                            center: {lat: -34.397, lng: 150.644},
                            zoom: 8
                        });
                }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAM2bjbYB9y0pRVKSzIRZUoaFhSyMu07I&callback=initMap" async defer></script>
        </div>
        <div class="large-6 small-12 left">
            {!! $project->body !!}
        </div>
    </div>

    <button href="#" onclick="window.history.back();">Ga terug</button>
</div>

<script>
    $(document).ready(function(){
        $(".project{{ $project->id }}").colorbox({rel:'project{{ $project->id }}', maxWidth:'50%', fixed: true});
    });
</script>