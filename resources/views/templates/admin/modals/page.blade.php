
<div class="modal fade" tabindex="-1" role="dialog" id="pagesModal" aria-labelledby="pagesModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>Pagina instellingen:</strong> {{ $page->meta_title }}</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('id' => 'pageForm', 'class' => 'form-horizontal')) !!}

                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    {!! Form::label('title', 'Pagina naam', ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('title',$page->title,array('class'=>'form-control','placeholder' => 'Pagina naam', 'required', 'autofocus')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('slug', 'Pagina link', ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon" id="page-url">{{ url("/ ") }}</span>
                            {!! Form::text('slug',($page->slug=="index") ? "" : $page->slug,array('class'=>'form-control', 'autofocus', 'aria-describedby' => 'page-url')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('meta_title', 'Pagina titel', ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::text('meta_title',$page->meta_title,array('class'=>'form-control', 'placeholder' => 'Pagina titel', 'required', 'autofocus')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('meta_desc', 'Pagina beschrijving', ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::textarea('meta_desc',$page->meta_desc,array('class'=>'form-control','placeholder' => 'Pagina beschrijving', 'autofocus')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('menu', 'Weergeven in menu', ['class' => 'col-md-3 control-label']) !!}

                    <div class="col-md-8">
                        {!! Form::checkbox('menu', 'menu', $page->menu) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                <button type="submit" form="pageForm" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        $('#pageForm').submit(function(){
            var slug = ($('input[name=slug]').val()) ? $('input[name=slug]').val() : "index";
            var message = $('.form-message');

            $.ajax({
                url: '{{ cms_url('/pages/'.$page->id) }}',
                type: 'PATCH',
                data: {
                    'title' : $('input[name=title]').val(),
                    'slug' : slug,
                    'meta_title' : $('input[name=meta_title]').val(),
                    'meta_desc' : $('textarea[name=meta_desc]').val(),
                    'menu' : ($('input[name=menu]').is(":checked")) ? 1 : 0,
                },
                success: function (data) {
                    window.location.href = '{{ cms_url("/") }}/'+data.page['slug'];
                },
                error: function(data){
                    var errors = data.responseJSON;
                    var errorMessage = "";

                    if (errors === undefined)
                        errorMessage += "Er ging iets fout, probeer het opnieuw.";

                    for (var error in errors) {
                        errorMessage += errors[error]+"<br />";
                    }

                    message.addClass("alert-danger").html(errorMessage).show();
                }
            });

            return false;
        });
    });
</script>
