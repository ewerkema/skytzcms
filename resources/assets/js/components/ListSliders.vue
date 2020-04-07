<template>
    <div>
        <div class="overview" v-if="!addImages">
            <div class="sidebar col-md-4">
                <ul class="list-group">
                    <a href="#" class="list-group-item"
                       v-for="slider in sliders"
                       :class="{ active: (slider.id == selectedSlider.id) }"
                       v-on:click="selectedSlider = slider"
                    >
                        <span class="badge">{{ slider.media.length }}</span>
                        {{ slider.name }}
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-on:click="newSlider = true"
                       v-if="!newSlider"
                    >
                        Voeg slider toe
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-if="newSlider"
                       :class="{ 'has-error': newSliderError }"
                    >
                        <input type="text" name="slider" class="form-control" @keyup.enter="createSlider()" placeholder="Slider naam" />
                        <button class="btn btn-default" v-on:click.prevent="newSlider = false">Annuleren</button>
                        <button class="btn btn-success right" v-on:click="createSlider()">Opslaan</button>
                    </a>
                </ul>
            </div>
            <div class="col-md-8" v-if="selectedSlider">
                <div class="bootstrap-row slider-row" v-for="row in chunkedMedia">
                    <div class="col-md-3 slider-image"
                         v-for="image in row"
                         v-on:click="removeImage(selectedSlider, image)"
                    >
                        <img :src="image.thumbnail_url" />
                        <span class="glyphicon glyphicon-remove hover remove"></span>
                    </div>
                </div>
                <p v-if="!hasImages(selectedSlider)">Er zijn geen afbeeldingen gevonden.</p>
                <button class="btn btn-success right" @click="enableAddImages">Afbeeldingen toevoegen</button>
                <button class="btn btn-danger right" v-on:click="removeSlider(selectedSlider)">Verwijder deze slider</button>
            </div>
            <div class="col-md-8" v-else>
                <p>Er is geen slider geselecteerd.</p>
            </div>
        </div>
        <div class="editForm" v-if="addImages">
            <select-media :multiple="true" :omit-images="selectedSlider.media" @send-images="storeImages" @cancel="addImages = false" ref="selectMedia"></select-media>
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

    .editForm button, .overview button.right {
        margin-left: 15px;
    }

    .editForm textarea {
        border-radius: 4px !important;
    }
</style>
<script>
    import AutoloadModal from './AutoloadModal.vue';
    import SelectMedia from './SelectMedia.vue';


    export default {
        extends: AutoloadModal,

        data(){
            return {
                sliders: [],
                selectedSlider: false,
                addImages: false,
                newSlider: false,
                newSliderError: false,
            };
        },

        components: {
            SelectMedia
        },

        computed: {
            chunkedMedia() {
                return _.chunk(this.selectedSlider.media, 4);
            }
        },

        methods: {
            hasImages: function (slider) {
                return slider.media.length;
            },

            enableAddImages: function() {
                this.addImages = true;
                this.$nextTick(() => {
                    this.loadImages();
                });
            },

            loadImages: function() {
                this.$refs.selectMedia.load();
            },

            storeImages: function (images) {
                let self = this;
                $.ajax({
                    url: '/cms/sliders/'+this.selectedSlider.id+'/media',
                    type: 'POST',
                    data: {
                        media: images
                    },
                    success: function (data) {
                        self.addImages = false;
                        self.loadSliders(self.selectedSlider.id);
                    }
                });
            },

            createSlider: function() {
                let value = $('[name=slider]').val();

                if (value == "") {
                    this.newSliderError = true;
                    alert("Slider naam kan niet leeg zijn.");
                    return;
                }

                this.newSliderError = false;
                this.newSlider = false;

                let self = this;
                $.ajax({
                    url: '/cms/sliders',
                    type: 'POST',
                    data: {
                        name: value,
                        colorbox: 1
                    },
                    success: function(data) {
                        self.sliders.push(data);
                        data.media = [];
                        self.selectedSlider = data;
                    },
                    error: function(data) {
                        let errors = data.responseJSON;
                        let errorMessage = "";

                        if (errors === undefined)
                            errorMessage += "Er ging iets fout, we zullen er zo spoedig mogelijk naar kijken.";
                        else {
                            for (let error in errors) {
                                errorMessage += errors[error]+"\n";
                            }
                        }
                        alert(errorMessage);
                    }
                });
            },

            removeSlider: function (slider) {
                let self = this;
                swal({
                    title: "Slider verwijderen?",
                    text: "Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze slider",
                }).then(function(){
                    self.doRemoveSlider(slider);
                }).done();
            },

            removeImage: function (slider, image) {
                let self = this;
                $.ajax({
                    url: '/cms/sliders/'+slider.id+'/media/'+image.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function (result) {
                        let index = self.selectedSlider.media.indexOf(image);
                        self.selectedSlider.media.splice(index, 1);
                    }
                });
            },

            doRemoveSlider: function (slider) {
                let self = this;
                $.ajax({
                    url: '/cms/sliders/'+slider.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = self.sliders.indexOf(slider);
                        self.sliders.splice(index, 1);
                        self.selectedSlider = _.head(self.sliders);
                    }
                });
            },

            loadFromDatabase: function() {
                return this.loadSliders(0);
            },

            loadSliders: function(selectedSliderId) {
                let self = this;
                return $.get('/cms/sliders', function (data) {
                    if (data.length != 0) {
                        self.sliders = data;
                        self.selectedSlider = (selectedSliderId) ? _.find(data, ['id', selectedSliderId]) : _.head(data);
                    }
                });
            },
        },
    }
</script>
