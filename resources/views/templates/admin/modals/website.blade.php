@extends('templates.admin.modals.modal', ['target'=>'websiteModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Website instellingen</strong></h4>
@overwrite

@section('modal-body')
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#mainTab" aria-controls="mainTab" role="tab" data-toggle="tab">Algemeen</a></li>
        <li role="presentation"><a href="#analyticsTab" aria-controls="analyticsTab" role="tab" data-toggle="tab">Google Analytics</a></li>
        <li role="presentation"><a href="#socialTab" aria-controls="socialTab" role="tab" data-toggle="tab">Social Media</a></li>
        <li role="presentation"><a href="#websiteHeaderTab" aria-controls="websiteHeaderTab" role="tab" data-toggle="tab">Header</a></li>
        <li role="presentation"><a href="#whatsappTab" aria-controls="whatsappTab" role="tab" data-toggle="tab">WhatsApp</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="alert form-message" role="alert" style="display: none;"></div>
    <form action="#" class="form-horizontal" id="websiteForm">
        <div class="tab-content" id="websiteTabs">
            <div role="tabpanel" class="tab-pane active" id="mainTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Footer tekst</label>

                    <div class="col-md-8">
                        <textarea type="text" name="footerblock" class="form-control" placeholder="Footer tekst" autofocus>{{ $settings['footerblock']->value }}</textarea>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="analyticsTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Google Analytics tracking ID:</label>

                    <div class="col-md-8">
                        <input type="text" name="googleanalytics" value="{{ $settings['googleanalytics']->value }}" class="form-control" placeholder="Tracking ID" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Tracking activeren:</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            {!! Form::checkbox('recordgoogle', 'recordgoogle', $settings['recordgoogle']->value) !!}
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="socialTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Facebook link</label>

                    <div class="col-md-8">
                        <input type="text" name="facebook_page" value="{{ $settings['facebook_page']->value }}" class="form-control" placeholder="Facebook link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Twitter link</label>

                    <div class="col-md-8">
                        <input type="text" name="twitter_page" value="{{ $settings['twitter_page']->value }}" class="form-control" placeholder="Twitter link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Youtube link</label>

                    <div class="col-md-8">
                        <input type="text" name="youtube_page" value="{{ $settings['youtube_page']->value }}" class="form-control" placeholder="Youtube link" autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Google Plus link</label>

                    <div class="col-md-8">
                        <input type="text" name="googleplus_page" value="{{ $settings['googleplus_page']->value }}" class="form-control" placeholder="Google Plus link" autofocus />
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="websiteHeaderTab">
                <p>Voeg een header toe aan alle pagina's. Als een pagina een eigen header heeft ingesteld, wordt deze gebruikt.</p>
                <div class="form-group">
                    <label for="header_id2" class="col-md-3 control-label">Header</label>

                    <div class="col-md-8">
                        <select class="form-control" id="header_id2" name="header_id">
                            <option value="0" {{ (!$settings['header_id']->value) ? "selected" : "" }}>Geen header</option>
                            @foreach (Header::all() as $header)
                                <option value="{{ $header->id }}" {{ ($settings['header_id']->value == $header->id) ? "selected" : ""}}>
                                    {{ $header->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="whatsappTab">
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">WhatsApp weergeven:</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            {!! Form::checkbox('display_whatsapp', 'display_whatsapp', $settings['display_whatsapp']->value) !!}
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp_number" class="col-md-3 control-label">WhatsApp nummer (zonder spaties):</label>

                    <div class="col-md-8">
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number']->value }}" class="form-control" placeholder="WhatsApp nummer (zonder spaties)" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp_display_number" class="col-md-3 control-label">WhatsApp nummer tekst:</label>

                    <div class="col-md-8">
                        <input type="text" name="whatsapp_display_number" value="{{ $settings['whatsapp_display_number']->value }}" class="form-control" placeholder="WhatsApp nummer tekst" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp_text" class="col-md-3 control-label">WhatsApp tekst:</label>

                    <div class="col-md-8">
                        <textarea type="text" name="whatsapp_text" class="form-control" placeholder="WhatsApp tekst op popup" autofocus>{{ $settings['whatsapp_text']->value }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp_timer" class="col-md-3 control-label">WhatsApp timer:</label>

                    <div class="col-md-8">
                        <input type="number" min="0" step="1" name="whatsapp_timer" value="{{ $settings['whatsapp_timer']->value }}" class="form-control" placeholder="WhatsApp timer" />
                    </div>
                </div>
            </div>
        </div>
    </form>
@overwrite

@section('modal-footer')
    <button type="submit" form="websiteForm" class="btn btn-primary">Opslaan</button>
@overwrite

@section('javascript')
    <script type="text/javascript">
        function selectMedia() {
            $('#selectMediaModal').modal('toggle');
        }

        $('#websiteTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        var request = new Request('{{ cms_url('settings') }}');
        request.setType('PATCH');
        request.setForm('#websiteForm');

        request.addFields([
            'footerblock',
            'googleanalytics',
            'facebook_page',
            'twitter_page',
            'youtube_page',
            'googleplus_page',
            'header_id',
            'whatsapp_number',
            'whatsapp_display_number',
            'whatsapp_text',
            'whatsapp_timer'
        ]);

        request.addField('recordgoogle', 'checkbox', false);
        request.addField('display_whatsapp', 'checkbox', false);

        request.onSubmit(function(data) {
            $('#websiteModal').modal('toggle');
            swal({
                title: 'Success!',
                text: 'Website instellingen zijn succesvol aangepast.',
                type: "success",
                timer: 2000
            });
        });

        var sliderSelect = $('[name=header_slider]');
        var imageInput = $('[name=header_image]');
        var imageInputName = $('[name=header_image_name]');

        sliderSelect.change(function() {
            var value = $(this).val();

            if (value) {
                imageInput.val(0);
                imageInputName.val("");
            }
        });

        imageInput.change(function() {
            var value = $(this).val();

            if (value)
                sliderSelect.val(sliderSelect.find('option:first').val());
        })
    </script>
@overwrite