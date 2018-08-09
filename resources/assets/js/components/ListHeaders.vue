<template>
    <div>
        <div class='list-headers' v-if="!selectedHeader">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Aanmaak datum</th>
                        <th></th>
                    </tr>
                    <tr v-for="(i, header) in headers">
                        <td>{{ i+1 }}</td>
                        <td>{{ header.name | truncate 30 }}</td>
                        <td>{{ header.created_at | moment "dddd, D MMMM YYYY" | capitalize }}</td>
                        <td>
                            <a href="#" v-on:click="editHeader(header)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" v-on:click="removeHeader(header.id)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!headers.length">
                        <td colspan="4">Er zijn geen headers gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" v-on:click="createHeader()">Nieuw header</button>
            </div>
        </div>
        <div class="editForm" v-if="selectedHeader">
            <form action="#" class="form-horizontal" id="headerForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" id="name" name="name" v-model="selectedHeader.name" class="form-control" placeholder="Naam" required />
                    </div>
                </div>

                <p><i>Selecteer één van de volgende opties:</i></p>
                <ul class="nav nav-tabs" role="tablist" id="headerTabs">
                    <li role="presentation" class="active"><a href="#imageTab" aria-controls="imageTab" role="tab" data-toggle="tab">Afbeelding</a></li>
                    <li role="presentation"><a href="#sliderTab" aria-controls="sliderTab" role="tab" data-toggle="tab">Slider</a></li>
                    <li role="presentation"><a href="#videoTab" aria-controls="videoTab" role="tab" data-toggle="tab">Video</a></li>
                </ul>
                <div class="tab-content" id="websiteTabs">
                    <div role="tabpanel" class="tab-pane active" id="imageTab">
                        <div class="form-group">
                            <label for="image_id" class="col-md-3 control-label">Afbeelding</label>

                            <div class="col-md-8">
                                <div class="input-group input-pointer">
                                    <input type="hidden" name="image_id" id="image_id" v-model="selectedHeader.image_id" class="form-control selected_media_id" @change="resetSliderVideo"/>
                                    <span class="input-group-addon" id="media-picture" onclick="selectMediaWithEdit()"><span class="glyphicon glyphicon-picture"></span></span>
                                    <input type="text" name="image_name" onclick="selectMediaWithEdit()" :value="selectedHeaderImageName || ''" class="form-control selected_media_name no-border-radius" placeholder="Artikel afbeelding" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="button" @click="removeMedia()"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-md-3 control-label">Tekst over afbeelding</label>

                            <div class="col-md-8">
                                <editor name="content" id="content" language="nl-NL" :model.sync="selectedHeader.content"></editor>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="position" class="col-md-3 control-label">Positie tekst</label>

                            <div class="col-md-8">
                                <select class="form-control" id="position" name="position" v-model="selectedHeader.position">
                                    <option v-for="(index,position) in positions" :value="index">
                                        {{ position }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="link_to_page" class="col-md-3 control-label">Link naar pagina</label>

                            <div class="col-md-8">
                                <select class="form-control" id="link_to_page" name="link_to_page" v-model="selectedHeader.link_to_page">
                                    <option value="0">Geen pagina</option>
                                    <option v-for="page in pages" :value="page.id">
                                        {{ page.title }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="link_to_url" class="col-md-3 control-label">Link naar url</label>

                            <div class="col-md-8">
                                <input type="text" name="link_to_url" id="link_to_url" v-model="selectedHeader.link_to_url" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="open_in_new_tab" class="col-md-3 control-label">Openen in nieuwe tab</label>

                            <div class="col-md-8">
                                <label class="Switch">
                                    <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" v-model="selectedHeader.open_in_new_tab">
                                    <div class="Switch__slider"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="sliderTab">
                        <div class="form-group">
                            <label for="slider_id" class="col-md-3 control-label">Slider</label>

                            <div class="col-md-8">
                                <select class="form-control" id="slider_id" name="slider_id" @change="resetImageVideo" v-model="selectedHeader.slider_id">
                                    <option value="0">Geen slider</option>
                                    <option v-for="slider in sliders" :value="slider.id">
                                        {{ slider.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="videoTab">
                        <div class="form-group">
                            <label for="video" class="col-md-3 control-label">Youtube embed url</label>

                            <div class="col-md-8">
                                <input type="text" id="video" name="video" v-model="selectedHeader.video" class="form-control" placeholder="Youtube embed url" @blur="validateYoutubeUrl(selectedHeader.video)" @change="resetImageSlider" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="headerForm" class="btn btn-success right" v-on:click="submitForm()">Header opslaan</button>
                        <button class="btn btn-default right" v-on:click="cancelEdit($event)">Annuleren</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<style>
    .add-item > .glyphicon {
        float: right;
        margin-right: 4px;
    }

    .table .right {
        float: right;
    }

    .editForm button {
        margin-left: 15px;
    }

    .editForm textarea {
        border-radius: 4px !important;
    }

    .list-headers .btn.right {
        margin-left: 15px;
    }
</style>
<script>
    import ListBase from './ListBase.vue';

    export default {
        extends: ListBase,

        data(){
            return {
                headers: [],
                sliders: [],
                pages: [],
                selectedHeader: false,
                selectedHeaderImageName: false,
                positions: ['Linksboven', 'Links', 'Linksonder', 'Midden boven', 'Midden', 'Midden onder', 'Rightsboven', 'Rechts', 'Rechtsonder'],
            };
        },

        components: {
            "editor": require('./vue-html-editor')
        },

        watch: {

            selectedHeader: function (val) {
                if (val && val.image_id) {
                    let _this = this;
                    $.ajax({
                        url: '/cms/media/'+val.image_id,
                        type: 'GET',
                        success: function(data) {
                            _this.selectedHeaderImageName = data.name;
                        },
                        error: function() {
                            alert("Er ging iets fout, probeer het later opnieuw");
                        }
                    });

                } else {
                    this.selectedHeaderImageName = false;
                }

                if (val) {
                    if (!this.selectedHeader.image_id && this.selectedHeader.slider_id === 0) {
                        $('#headerTabs a[href="#videoTab"]').tab('show');
                    }

                    if (!this.selectedHeader.image_id && this.selectedHeader.video.length === 0) {
                        $('#headerTabs a[href="#sliderTab"]').tab('show');
                    }

                    if (!this.selectedHeader.slider_id && this.selectedHeader.video.length === 0) {
                        $('#headerTabs a[href="#imageTab"]').tab('show');
                    }
                }
            }

        },

        methods: {
            submitForm: function () {
                let request = new Request('/cms/headers');
                request.setForm('#headerForm');
                request.setType('POST');

                request.addFields(['name', 'image_id', 'slider_id', 'video', 'content', 'link_to_page', 'link_to_url', 'position']);
                request.addCheckboxes(['open_in_new_tab']);

                if (this.selectedHeader.id != undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedHeader.id);
                }

                let _this = this;
                request.send(function(data) {
                    _this.selectedHeader = false;
                    _this.loadHeaders();
                    if (request.getType() == 'POST') {
                        swal({
                            title: "Header opgeslagen!",
                            text: 'Header is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Header aangepast!",
                            text: 'Header is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            validateYoutubeUrl: function(val) {
                let validUrl = this.validYoutubeUrl(val);
                if (!validUrl && val !== '') {
                    swal({
                        title: 'Onjuiste url',
                        text: 'De opgegeven url is een onjuiste youtube embed url.',
                        type: 'warning',
                    })
                } else {
                    this.selectedHeader.video = validUrl;
                }
            },

            validYoutubeUrl: function(url) {
                if (url !== undefined || url !== '') {
                    let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                    let match = url.match(regExp);
                    if (match && match[2].length === 11) {
                        return `https://www.youtube.com/embed/${match[2]}?autoplay=0`;
                    } else {
                        return false;
                    }
                }
            },

            createHeader: function () {
                this.selectedHeader = {
                    'name': '',
                    'position': 0,
                    'image_id': false,
                    'slider_id': false,
                    'video': '',
                    'content': '',
                    'link_to_page': false,
                    'link_to_url': '',
                    'open_in_new_tab': false,
                };
            },

            removeMedia: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeaderImageName = false;
            },

            editHeader: function (header) {
                header.video = header.video ? header.video : '';
                header.slider_id = header.slider_id ? header.slider_id : 0;
                header.link_to_page = header.link_to_page ? header.link_to_page : 0;
                header.open_in_new_tab = header.open_in_new_tab ? header.open_in_new_tab : 0;
                this.selectedHeader = header;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedHeader = false;
            },

            addMedia: function(inputId) {
                $('.input-image').each(function() {
                    $(this).find('.selected_media_id').removeClass('selected_media_id');
                    $(this).find('.selected_media_name').removeClass('selected_media_name');
                });

                let nameEl = $('#image_name_' + inputId);
                let imageEl = $('#image_' + inputId);

                imageEl.addClass('selected_media_id');
                nameEl.addClass('selected_media_name');

                selectMedia();
            },

            removeHeader: function (headerId) {
                let _this = this;
                swal({
                    title: "Header verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze header",
                }).then(function(){
                    _this.doRemoveHeader(headerId);
                }).done();
            },

            doRemoveHeader: function (headerId) {
                let _this = this;
                $.ajax({
                    url: '/cms/headers/'+headerId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.headers.$remove(_.find(_this.headers, ['id', headerId]));
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadHeaders();
                this.loadSliders();
                this.loadPages();
            },

            loadHeaders: function() {
                let _this = this;
                $.get('/cms/headers', function (data) {
                    _this.headers = data;
                });
            },

            loadSliders: function() {
                let _this = this;
                $.get('/cms/sliders', function (data) {
                    _this.sliders = data;
                });
            },

            loadPages: function() {
                let _this = this;
                $.get('/cms/pages', function (data) {
                    _this.pages = data;
                });
            },

            resetSliderVideo: function() {
                this.selectedHeader.slider_id = 0;
                this.selectedHeader.video = '';
            },

            resetImageVideo: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeader.position = 0;
                this.selectedHeader.content = '';
                this.selectedHeader.link_to_page = 0;
                this.selectedHeader.link_to_url = '';
                this.selectedHeader.open_in_new_tab = false;
                this.selectedHeader.video = '';
            },

            resetImageSlider: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeader.position = 0;
                this.selectedHeader.content = '';
                this.selectedHeader.link_to_page = 0;
                this.selectedHeader.link_to_url = '';
                this.selectedHeader.open_in_new_tab = false;
                this.selectedHeader.slider_id = 0;
            }
        },
    }
</script>
