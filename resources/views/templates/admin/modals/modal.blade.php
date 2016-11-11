<div class="modal {{ isset($animation) ? $animation : 'fade' }}" data-backdrop="{{ isset($backdrop) ? $backdrop : '' }}" tabindex="-1" role="dialog" id="{{ $target }}" aria-labelledby="{{ $target }}">
    <div class="modal-dialog modal-lg" role="document">
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
    </div>
</div>

@yield('javascript')