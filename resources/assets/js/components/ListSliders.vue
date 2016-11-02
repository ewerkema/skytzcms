<template>
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
            <div class="bootstrap-row slider-row" v-for="row in selectedSlider.media | chunk 4">
                <div class="col-md-3 slider-image"
                     v-for="image in row"
                     v-on:click="removeImage(selectedSlider, image)"
                >
                    <img :src="imagePath(image.path)" />
                    <span class="glyphicon glyphicon-remove hover remove"></span>
                </div>
            </div>
            <p v-if="!hasImages(selectedSlider)">Er zijn geen afbeeldingen gevonden.</p>
            <button class="btn btn-success right" v-on:click="addImages = true">Afbeeldingen toevoegen</button>
            <button class="btn btn-danger right" v-on:click="removeSlider(selectedSlider)">Verwijder deze slider</button>
        </div>
    </div>
    <div class="editForm" v-if="addImages">
        <form action="#" class="form-horizontal" id="SliderForm" v-on:submit.prevent>
            <div class="alert form-message" role="alert" style="display: none;"></div>
            <div class="bootstrap-row slider-row" v-for="row in images | chunk 4">
                <div class="col-md-3 slider-image"
                     v-for="image in row"
                     v-on:click="selectImage(image)"
                     :class="{selected: isSelected(image) }"
                >
                    <img :src="imagePath(image.path)" />
                    <span class="glyphicon glyphicon-ok add"></span>
                </div>
            </div>
            <p v-if="images.length == 0">Er zijn geen afbeeldingen (meer) gevonden.</p>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-3">
                    <button form="sliderForm" class="btn btn-success right" v-on:click="submitForm()">Afbeeldingen toevoegen</button>
                    <button class="btn btn-default right" v-on:click.prevent="addImages = false">Annuleren</button>
                </div>
            </div>
        </form>
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

    .slider-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .slider-image {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .slider-image .glyphicon {
        position: absolute;
        font-size: 20px;
        z-index: 100;
        font-size: 40px;
        cursor: pointer;
        display: none;
        top: 50%;
        transform: translate(0,-50%);
    }

    .slider-image .remove {
        color: #bf5329;
    }

    .slider-image .add {
        color: #2ab27b;
        text-shadow: 0px 0px 5px #000;
    }

    .slider-image img {
        transition: opacity 0.5s ease;
        -webkit-backface-visibility: hidden;
    }

    .slider-image:hover img {
        opacity: 0.5;
        filter: alpha(opacity=50);
    }

    .slider-image:hover .hover, .slider-image.selected .glyphicon {
        display: block;
    }

</style>
<script>
    export default {
        data(){
            return {
                sliders: [],
                selectedSlider: false,
                addImages: false,
                selectedImages: [],
                images: [],
                newSlider: false,
                newSliderError: false
            };
        },

        created() {
            this.loadFromDatabase();
        },

        watch: {
            addImages: function (active) {
                this.images = [];
                this.selectedImages = [];
                if (active) {
                    this.loadImages();
                }
            }
        },

        methods: {

            imagePath: function(path) {
                return '/'+path;
            },

            hasImages: function (slider) {
                return slider.media.length;
            },

            selectImage: function (image) {
                if (this.isSelected(image)) {
                    this.selectedImages.$remove(image.id);
                } else {
                    this.selectedImages.push(image.id);
                }
            },

            isSelected: function (image) {
                return _.includes(this.selectedImages, image.id);
            },

            submitForm: function () {
                var _this = this;
                $.ajax({
                    url: '/cms/sliders/'+this.selectedSlider.id+'/media',
                    type: 'POST',
                    data: {
                        media: _this.selectedImages
                    },
                    success: function (data) {
                        _this.addImages = false;
                        _this.loadFromDatabase();
                    }
                });
            },

            createSlider: function() {
                var value = $('[name=slider]').val();

                if (value == "") {
                    this.newSliderError = true;
                    alert("Slider naam kan niet leeg zijn.");
                    return;
                }

                this.newSliderError = false;
                this.newSlider = false;

                var _this = this;
                $.ajax({
                    url: '/cms/sliders',
                    type: 'POST',
                    data: {
                        name: value,
                        colorbox: 1
                    },
                    success: function(data) {
                        _this.sliders.push(data);
                        data.media = [];
                        _this.selectedSlider = data;
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        var errorMessage = "";

                        if (errors === undefined)
                            errorMessage += "Er ging iets fout, we zullen er zo spoedig mogelijk naar kijken.";
                        else {
                            for (var error in errors) {
                                errorMessage += errors[error]+"\n";
                            }
                        }
                        alert(errorMessage);
                    }
                });
            },

            removeSlider: function (slider) {
                var _this = this;
                swal({
                    title: "Slider verwijderen?",
                    text: "Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit slider",
                }).then(function(){
                    _this.doRemoveSlider(slider);
                    _this.selectedSlider = _.head(_this.sliders);
                }).done();
            },

            removeImage: function (slider, image) {
                var _this = this;
                $.ajax({
                    url: '/cms/sliders/'+slider.id+'/media/'+image.id,
                    type: 'DELETE',
                    success: function (result) {
                        _this.selectedSlider.media.$remove(image);
                    }
                });
            },

            doRemoveSlider: function (slider) {
                var _this = this;
                $.ajax({
                    url: '/cms/sliders/'+slider.id,
                    type: 'DELETE',
                    success: function(result) {
                        _this.sliders.$remove(slider);
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadSliders();
            },

            loadSliders: function() {
                var _this = this;
                $.get('/cms/sliders', function (data) {
                    if (data.length != 0) {
                        _this.sliders = data;
                        _this.selectedSlider = _.head(data);
                    }
                });
            },

            loadImages: function() {
                var _this = this;
                $.get('/cms/media', function (data) {
                     _this.images = _this.filterImages(data);
                });
            },

            isImage: function (image) {
                var extensions = ['jpg', 'png', 'tif', 'jpeg', 'gif'];
                return _.includes(extensions, image.extension.toLowerCase());
            },

            imageExistsInSelectedSlider: function (image) {
                return _.find(this.selectedSlider.media, ['id', image.id]);
            },

            filterImages: function (images) {
                var _this = this;
                return _.filter(images, function (image) {
                    return _this.isImage(image) && !_this.imageExistsInSelectedSlider(image);
                });
            }

        },

        computed: {

        }
    }
</script>
