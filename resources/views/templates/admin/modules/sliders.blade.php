<div class="slider-wrapper" style="height: 300px;">
    <div id="slider-{{ $id }}" style="height:300px;">
        @foreach (Slider::find($id)->get()->images() as $image)
            <div class="slide{{ $image->id }}"><img src="{{ $image->path }}'" alt="" /></div>
        @endforeach
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