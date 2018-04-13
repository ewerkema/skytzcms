<div class="modal fade" tabindex="-1" role="dialog" id="mediaModal" aria-labelledby="mediaModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Media overzicht</strong></h4>
            </div>
            <div class="modal-body">
                <list-media target="#mediaModal"></list-media>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="submit" data-toggle="modal" data-target="#uploadMediaModal" form="mediaForm" class="btn btn-success">Nieuwe media uploaden</button>
            </div>
        </div>
        <div id='spinner' class="modal-overlay"></div>
    </div>
</div>