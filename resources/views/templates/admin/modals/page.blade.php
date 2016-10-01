
<div class="modal fade" tabindex="-1" role="dialog" id="pagesModal" aria-labelledby="pagesModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Pagina instellingen:</strong> {{ $page->meta_title }}</h4>
            </div>
            <div class="modal-body">
                <div class="bootstrap-row">
                    <form action="{{ cms_url('/pages/'.$page->id.'/edit') }}" id="pageForm">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">Voornaam</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
</div>
