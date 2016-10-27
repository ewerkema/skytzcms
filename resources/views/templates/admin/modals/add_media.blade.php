{{--Start  add new media--}}
<div class="modal fade" tabindex="-1" role="dialog" id="uploadMediaModal" aria-labelledby="uploadMediaModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id='close-modal'data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong>New media</strong></h4>
            </div>
            {!!Former::framework('Nude')!!}
            {!! Former::open()->action( URL::route("media.store") )->method('post')->enctype("multipart/form-data")->class('p-t-15')->role('form')->id('media-form') !!}
                <div class="modal-body">
                    <div id="container">
                        <div id="filelist"></div>
                        <br />
                        <div class="upload-button">
                            <div id='pickfiles' class="btn btn-primary btn-cons m-b-10">
                                <i class="fa fa-cloud-upload"></i>Upload
                            </div>
                        </div>

                        <div id="console" class='error'></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                    {{-- {!!Former::button('Save')->disabled(true)->class('btn btn-success')!!} --}}
                    {!!Former::submit('Save')->disabled(true)->class('btn btn-success')!!}
                </div> 
            {!! Former::close() !!}
        </div>
        <div id='spinner1' class="modal-overlay"></div>
    </div>
</div>
{{--end add new media--}}
<script type="text/javascript">
    $(document).ready(function(){
        $('#spinner1').hide();
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles', // you can pass in id...
            container: document.getElementById('container'), // ... or DOM Element itself
            multi_selection:true,
            url : "/plupload/upload.php",
            filters : {
                max_file_size : '25mb',
                mime_types: [
                {title : "Image file", extensions : "jpg,jpeg,gif,png"},
                {title : "Document file", extensions : "pdf,docx,doc"}
                ]
            },
            // Flash settings
            flash_swf_url : '/plupload/js/Moxie.swf',

            // Silverlight settings
            silverlight_xap_url : '/plupload/js/Moxie.xap',

            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {
                     plupload.each(files, function(file) {
                         file.name = file.id + '.'+file.name.split(".").pop();
                     });
                     if(files.length > 0)
                     {
                        $('input[type="submit"]').prop('disabled', false);
                     }
                    uploader.start();
                },
                FileUploaded:function(up,file)
                {
                    $('#console').html('');
                    var ext =file.name.split(".").pop();
                    if(ext !='doc' && ext != 'docx' && ext != 'pdf')
                    {
                        $('#filelist').append(
                          "<div class='col-xs-6 col-md-3'  id="+file.id+"><div class='media-image-gallery'><div class='thumbnail' style='background: url(/tmp/"+file.name+") center center / cover no-repeat;'> <div class='trash'><a href='javascript:;' class='remove'><span class='glyphicon glyphicon-trash'></span></a></div></div><p>"+file.name+"</p></div><input name='name[]' id='photo' value="+file.name+" type='hidden'> </div> <b></b>"
                        );    
                    }
                    else
                    {
                        $('#filelist').append(
                          "<div class='col-xs-6 col-md-3'  id="+file.id+"><div class='media-image-gallery'><div class='thumbnail' ><img src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU3Y2U1YWRhN2YgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTdjZTVhZGE3ZiI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI2MSIgeT0iOTQuNSI+MTcxeDE4MDwvdGV4dD48L2c+PC9nPjwvc3ZnPg=='' alt='Document'> <div class='trash'><a href='javascript:;' class='remove'><span class='glyphicon glyphicon-trash'></span></a></div></div><p>"+file.name+"</p></div><input name='name[]' id='photo' value="+file.name+" type='hidden'> </div><b></b> "
                        );    
                    }

                    
                    $('#' + file.id + ' a.remove').first().click(function(e) {
                        e.preventDefault();
                        up.removeFile(file);
                        $('#' + file.id).remove();
                        if(uploader.files.length == 0)
                        {
                           $('input[type="submit"]').prop('disabled', true); 
                        }
                    });


                },
                Error: function(up, err) {
                    if(err.code==-601)
                        document.getElementById('console').innerHTML += "Please upload jpg,png or pdf file";
                    else if(err.code==-600)
                        document.getElementById('console').innerHTML += "File size is not more than 4 MB";
                    else
                        document.getElementById('console').innerHTML += err.message;
                    $("#spinner").hide();
                }
            }
        });
        uploader.init();
        $('#media-form').submit(function(e){
            e.preventDefault();
            $('#spinner1').show();
            var url='/cms/media';
            $.ajax({
                type: "POST",
                url: url,
                data: $( "#media-form" ).serialize(),
                success: function( data ) {
                    if(data.status == 'success')
                    {
                        $('#spinner1').hide();
                        $('#filelist').html('');
                        $('#uploadMediaModal').modal('hide');
                        $('#spinner').show();
                        $.ajax({
                            url: '/cms/get-media/?page='+ 1
                        }).done(function(data){
                            $('#spinner').hide();
                            $('.bootstrap-row').html(data);
                        });  
                    }
                }
            });

        });
        $('#close-modal').click(function(){
            $('#filelist').html('');
            $('#uploadMediaModal').modal('hide');
        });
        
    });
</script>