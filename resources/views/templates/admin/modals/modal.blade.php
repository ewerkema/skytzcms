<div class="modal {{ isset($animation) ? $animation : 'fade' }}" data-backdrop="{{ isset($backdrop) ? $backdrop : 'true' }}" tabindex="-1" role="dialog" id="{{ $target }}" aria-labelledby="{{ $target }}">
    <div class="modal-dialog {{ (isset($fullscreen) && $fullscreen) ? "modal-full-screen" : "modal-lg" }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                @yield('modal-header')
            </div>
            <div class="modal-body">
                @yield('modal-body')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                @yield('modal-footer')
            </div>
        </div>
        <div class="spinner modal-overlay" style="display:none;"></div>
    </div>
</div>

@yield('javascript')