<div class="slider-wrapper">
    <div id="slider-{{ $id }}" class="slider">
        @if ($slider = Slider::find($id))
            @foreach ($slider->media()->get() as $image)
                <div class="slide slide{{ $image->id }}"><img src="{{ $image->photo_url('original') }}" alt="{{ $image->description }}" /></div>
            @endforeach
        @else
            <p>Deze slider bestaat niet meer / is verwijderd.</p>
        @endif
    </div>
    <div id="slider-direction-nav"></div>
    <div id="slider-control-nav"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var slider = $('#slider-{{ $id }}').leanSlider({
            directionNav: '#slider-direction-nav',
            controlNav: '#slider-control-nav'
        });
    });
</script>