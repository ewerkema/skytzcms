<div id="social-block-{{ $id }}" class="social_block">
    @if ($social = Social::find($id))
        @include('templates.admin.modules.social.'.$social->type)
    @else
        <p>Dit social media item bestaat niet meer / is verwijderd.</p>
    @endif
</div>
