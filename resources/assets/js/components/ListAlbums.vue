<template>
    <div>
        <div class="overview" v-if="!addImages && !changeOrder">
            <div class="sidebar col-md-4">
                <ul class="list-group">
                    <a href="#" class="list-group-item"
                       v-for="album in albums"
                       :class="{ active: (album.id == selectedAlbum.id) }"
                       v-on:click="selectedAlbum = album"
                    >
                        <span class="badge">{{ album.media.length }}</span>
                        {{ album.name }}
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-on:click="newAlbum = true"
                       v-if="!newAlbum"
                    >
                        Voeg album toe
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-if="newAlbum"
                       :class="{ 'has-error': newAlbumError }"
                    >
                        <input type="text" name="album" class="form-control" @keyup.enter="createAlbum()" placeholder="Album naam" />
                        <button class="btn btn-default" v-on:click.prevent="newAlbum = false">Annuleren</button>
                        <button class="btn btn-success right" v-on:click="createAlbum()">Opslaan</button>
                    </a>
                </ul>
            </div>
            <div class="col-md-8" v-if="selectedAlbum">
                <div class="bootstrap-row album-row" v-for="row in selectedAlbum.media | chunk 4">
                    <div class="col-md-3 album-image"
                         v-for="image in row"
                         v-on:click="removeImage(selectedAlbum, image)"
                    >
                        <img :src="imagePath(image.path)" />
                        <span class="glyphicon glyphicon-remove hover remove"></span>
                    </div>
                </div>
                <p v-if="!hasImages(selectedAlbum)">Er zijn geen afbeeldingen gevonden.</p>
                <button class="btn btn-success right" v-on:click="addImages = true">Afbeeldingen toevoegen</button>
                <button class="btn btn-default right" v-on:click="changeOrder = true">Volgorde aanpassen <span class="glyphicon glyphicon-sort"></span></button>
                <button class="btn btn-danger right" v-on:click="removeAlbum(selectedAlbum)">Verwijder dit album</button>
            </div>
            <div class="col-md-8" v-else>
                <p>Er is geen album geselecteerd.</p>
            </div>
        </div>
        <div class="editForm" v-if="addImages">
            <form action="#" class="form-horizontal" id="AlbumForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="bootstrap-row album-row" v-for="row in images | chunk 4">
                    <div class="col-md-3 album-image"
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
                        <button form="albumForm" class="btn btn-success right" v-on:click="submitForm()">Afbeeldingen toevoegen</button>
                        <button class="btn btn-default right" v-on:click.prevent="addImages = false">Annuleren</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="editForm" v-if="changeOrder">
            <form action="#" class="form-horizontal" id="ChangeAlbumOrder" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <p>Verander de volgorde van het album door de afbeeldingen te slepen:</p>
                <ul class="albumSortable sortable">
                    <li class="menu-item" :id="'image_' + image.id" :data-id="image.id" v-for="image in selectedAlbum.media">
                        <div>
                            <span class="glyphicon glyphicon-move"></span> <img :src="imagePath(image.path)" />
                        </div>
                    </li>
                </ul>
                <p v-if="selectedAlbum.media.length == 0">Er zijn geen afbeeldingen in dit album.</p>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="albumForm" class="btn btn-success right" v-on:click="updateOrder()">Volgorde opslaan</button>
                        <button class="btn btn-default right" v-on:click.prevent="changeOrder = false">Annuleren</button>
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

    .editForm ul li img {
        max-height: 30px;
        display: inline-block;
    }

    .editForm ul li {
        padding: 0;
    }

    .editForm button, .overview button.right {
        margin-left: 15px;
    }

    .editForm textarea {
        border-radius: 4px !important;
    }

    .album-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .album-image {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .album-image .glyphicon {
        position: absolute;
        font-size: 20px;
        z-index: 100;
        font-size: 40px;
        cursor: pointer;
        display: none;
        top: 50%;
        transform: translate(0,-50%);
    }

    .album-image .remove {
        color: #bf5329;
    }

    .album-image .add {
        color: #2ab27b;
        text-shadow: 0px 0px 5px #000;
    }

    .album-image img {
        transition: opacity 0.5s ease;
        -webkit-backface-visibility: hidden;
    }

    .album-image:hover img {
        opacity: 0.5;
        filter: alpha(opacity=50);
    }

    .album-image:hover .hover, .album-image.selected .glyphicon {
        display: block;
    }

</style>
<script>
    export default {
        data(){
            return {
                albums: [],
                selectedAlbum: false,
                addImages: false,
                selectedImages: [],
                images: [],
                newAlbum: false,
                newAlbumError: false,
                changeOrder: false
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
            },
            changeOrder: function (open) {
                if (open) {
                    $('.albumSortable').nestedSortable({
                        listType: 'ul',
                        handle: 'div',
                        items: 'li',
                        toleranceElement: '> div',
                        maxLevels: 1,
                        placeholder: 'placeholder',
                        forcePlaceholderSize: true
                    });
                }
            }
        },

        methods: {

            imagePath: function(path) {
                return '/'+path;
            },

            hasImages: function (album) {
                return album.media.length;
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
                    url: '/cms/albums/'+this.selectedAlbum.id+'/media',
                    type: 'POST',
                    data: {
                        media: _this.selectedImages
                    },
                    success: function (data) {
                        _this.addImages = false;
                        _this.loadAlbums(_this.selectedAlbum.id);
                    }
                });
            },

            updateOrder: function() {
                var _this = this;
                var sortedArray = $('.albumSortable').nestedSortable('toArray');
                var sorted = [];
                _.each(sortedArray, function (image) {
                        if (image.id !== undefined)
                    sorted.push(image.id);
                });
                $.ajax({
                    url: '/cms/albums/'+this.selectedAlbum.id+'/order',
                    type: 'POST',
                    data: {
                        _method: 'PATCH',
                        order: sorted
                    },
                    success: function (data) {
                        _this.changeOrder = false;
                        _this.loadAlbums(_this.selectedAlbum.id);

                        swal({
                            title: "Success!",
                            text: "Volgorde is succesvol aangepast.",
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            createAlbum: function() {
                var value = $('[name=album]').val();

                if (value == "") {
                    this.newAlbumError = true;
                    alert("Album naam kan niet leeg zijn.");
                    return;
                }

                this.newAlbumError = false;
                this.newAlbum = false;

                var _this = this;
                $.ajax({
                    url: '/cms/albums',
                    type: 'POST',
                    data: {
                        name: value,
                        colorbox: 1
                    },
                    success: function(data) {
                        _this.albums.push(data);
                        data.media = [];
                        _this.selectedAlbum = data;
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

            removeAlbum: function (album) {
                var _this = this;
                swal({
                    title: "Album verwijderen?",
                    text: "Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit album",
                }).then(function(){
                    _this.doRemoveAlbum(album);
                }).done();
            },

            removeImage: function (album, image) {
                var _this = this;
                $.ajax({
                    url: '/cms/albums/'+album.id+'/media/'+image.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function (result) {
                        _this.selectedAlbum.media.$remove(image);
                    }
                });
            },

            doRemoveAlbum: function (album) {
                var _this = this;
                $.ajax({
                    url: '/cms/albums/'+album.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.albums.$remove(album);
                        _this.selectedAlbum = _.head(_this.albums);
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadAlbums(0);
            },

            loadAlbums: function(selectedAlbumId) {
                var _this = this;
                $.get('/cms/albums', function (data) {
                    if (data.length != 0) {
                        _this.albums = data;
                        _this.selectedAlbum = selectedAlbumId ? _.find(data, ['id', selectedAlbumId]) : _.head(data);
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

            imageExistsInSelectedAlbum: function (image) {
                return _.find(this.selectedAlbum.media, ['id', image.id]);
            },

            filterImages: function (images) {
                var _this = this;
                return _.filter(images, function (image) {
                    return _this.isImage(image) && !_this.imageExistsInSelectedAlbum(image);
                });
            }

        },

        computed: {

        }
    }
</script>
