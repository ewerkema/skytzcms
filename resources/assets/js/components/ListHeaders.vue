<template>
    <div>
        <div class='list-headers' v-if="!editMode()">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Aanmaak datum</th>
                        <th></th>
                    </tr>
                    <tr v-for="(header, i) in headers">
                        <td>{{ i+1 }}</td>
                        <td>{{ header.name | truncate(30) }}</td>
                        <td>{{ header.created_at | moment("dddd, D MMMM YYYY") | capitalize }}</td>
                        <td>
                            <a href="#" @click="editHeader(header)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" @click="removeHeader(header.id)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!headers.length">
                        <td colspan="4">Er zijn geen headers gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" @click="createHeader()">Nieuw header</button>
            </div>
        </div>
        <div class="editForm" v-if="editMode()">
            <form action="#" class="form-horizontal" id="headerForm" @submit.prevent>
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
                                    <input type="hidden" name="image_id" id="image_id" v-model="selectedHeader.image_id" class="form-control" @change="resetSliderVideo"/>
                                    <span class="input-group-addon" id="media-picture" @click="$root.selectMediaWithEdit()"><span class="glyphicon glyphicon-picture"></span></span>
                                    <input type="text" name="image_name" @click="$root.selectMediaWithEdit()" v-model="selectedHeader.image_name" class="form-control no-border-radius" placeholder="Afbeelding" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="button" @click="removeMedia()"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="col-md-3 control-label">Tekst over afbeelding</label>

                            <div class="col-md-8">
                                <vue-editor v-model="content" @text-change="onTextChange"></vue-editor>
                                <input type="hidden" name="content" id="content" v-model="selectedHeader.content">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="position" class="col-md-3 control-label">Positie tekst</label>

                            <div class="col-md-8">
                                <select class="form-control" id="position" name="position" v-model="selectedHeader.position">
                                    <option v-for="(position, index) in positions" :value="index">
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
                        <button form="headerForm" class="btn btn-success right" @click="submitForm()">Header opslaan</button>
                        <button class="btn btn-default right" @click="cancelEdit">Annuleren</button>
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
    import AutoloadModal from './AutoloadModal.vue';
    import { VueEditor } from 'vue2-editor';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                headers: [],
                sliders: [],
                pages: [],
                selectedHeader: {},
                content: "",
                positions: ['Linksboven', 'Links', 'Linksonder', 'Midden boven', 'Midden', 'Midden onder', 'Rechtsboven', 'Rechts', 'Rechtsonder'],
            };
        },

        components: { VueEditor },

        mounted() {
            this.$root.$on('insert-image', (image) => {
                this.selectedHeader.image_id = image.id;
                this.selectedHeader.image_name = image.name;
            });
        },

        watch: {
            selectedHeader: function (val) {
                if (val && val.image_id) {
                    let self = this;
                    $.ajax({
                        url: '/cms/media/'+val.image_id,
                        type: 'GET',
                        success: function(data) {
                            self.selectedHeader.image_name = data.name;
                            self.$forceUpdate();
                        },
                        error: function() {
                            alert("Er ging iets fout, probeer het later opnieuw");
                        }
                    });

                } else if (this.editMode()) {
                    this.selectedHeader.image_name = "";
                }

                if (this.editMode()) {
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
            editMode() {
                return !_.isEmpty(this.selectedHeader);
            },

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

                let self = this;
                request.send(function(data) {
                    self.selectedHeader = {};
                    self.loadHeaders();
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
                    name: '',
                    position: 0,
                    image_id: 0,
                    image_name: '',
                    slider_id: 0,
                    video: '',
                    content: '',
                    link_to_page: 0,
                    link_to_url: '',
                    open_in_new_tab: false,
                };

                this.content = '';
            },

            onTextChange: function() {
                this.selectedHeader.content = this.content;
            },

            removeMedia: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeader.image_name = '';
            },

            editHeader: function (header) {
                header.video = header.video ? header.video : '';
                header.slider_id = header.slider_id ? header.slider_id : 0;
                header.link_to_page = header.link_to_page ? header.link_to_page : 0;
                header.open_in_new_tab = header.open_in_new_tab ? header.open_in_new_tab : 0;
                this.content = header.content;

                this.$nextTick(() => {
                    this.selectedHeader = header;
                });
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedHeader = {};
            },

            removeHeader: function (headerId) {
                let self = this;
                swal({
                    title: "Header verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze header",
                }).then(function(){
                    self.doRemoveHeader(headerId);
                }).done();
            },

            doRemoveHeader: function (headerId) {
                let self = this;
                $.ajax({
                    url: '/cms/headers/'+headerId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = _.findIndex(self.headers, o => o.id === headerId);
                        self.headers.splice(index, 1);
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadHeaders();
                this.loadSliders();
                this.loadPages();
            },

            loadHeaders: function() {
                let self = this;
                $.get('/cms/headers', function (data) {
                    self.headers = data;
                });
            },

            loadSliders: function() {
                let self = this;
                $.get('/cms/sliders', function (data) {
                    self.sliders = data;
                });
            },

            loadPages: function() {
                let self = this;
                $.get('/cms/pages', function (data) {
                    self.pages = data;
                });
            },

            resetSliderVideo: function() {
                this.selectedHeader.slider_id = 0;
                this.selectedHeader.video = '';
            },

            resetImageVideo: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeader.image_name = '';
                this.selectedHeader.position = 0;
                this.selectedHeader.content = '';
                this.selectedHeader.link_to_page = 0;
                this.selectedHeader.link_to_url = '';
                this.selectedHeader.open_in_new_tab = false;
                this.selectedHeader.video = '';
            },

            resetImageSlider: function() {
                this.selectedHeader.image_id = 0;
                this.selectedHeader.image_name = '';
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
