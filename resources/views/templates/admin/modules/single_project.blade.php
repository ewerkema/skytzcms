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
        @if ($project->address != null)
            <div class="large-6 small-12 left">
                <div id="projectMap" class="flex-video"></div>

                <script>
                    let map;
                    let infowindow;
                    function initMap() {
                        let mapElement = document.getElementById('projectMap');
                        if (mapElement !== null) {
                            map = new google.maps.Map(mapElement, {
                                center: new google.maps.LatLng(0,0),
                                zoom: 8,
                                zoomControl: true,
                                scaleControl: false,
                                scrollwheel: false,
                                disableDoubleClickZoom: true,
                            });
                            let service = new google.maps.places.PlacesService(map);
                            let request = {
                                location: map.getCenter(),
                                radius: '500',
                                query: "{{ $project->address }}"
                            };
                            service.textSearch(request, callback);
                            infowindow = new google.maps.InfoWindow();
                        }
                    }

                    function callback(results, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            map.setCenter(results[0].geometry.location);
                            let marker = new google.maps.Marker({
                                map: map,
                                animation: google.maps.Animation.DROP,
                                place: {
                                    placeId: results[0].place_id,
                                    location: results[0].geometry.location
                                }
                            });
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.setContent(`<div><strong>${results[0].name}</strong><br>${results[0].formatted_address}</div>`);
                                infowindow.open(map, this);
                            });
                        }
                    }
                </script>
                <script src="https://maps.googleapis.com/maps/api/js?key={{ config('skytz.google_maps') }}&libraries=places&callback=initMap" async defer></script>
            </div>
        @endif
        <div class="large-{{ ($project->address == null) ? '12' : '6' }} small-12 left">
            {!! $project->body !!}
        </div>
    </div>

    <button href="#" onclick="window.history.back();">Ga terug</button>
</div>

<script>
    $(document).ready(function(){
        $(".project{{ $project->id }}").colorbox({rel:'project{{ $project->id }}', maxWidth:'50%', maxHeight:'100%', fixed: true});
    });
</script>