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
                        <img :src="image.thumbnail_url" />
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
            <select-media :multiple="true" :omit-images="selectedAlbum.media" @send-images="storeImages" @cancel="addImages = false"></select-media>
        </div>
        <div class="editForm" v-if="changeOrder">
            <form action="#" class="form-horizontal" id="ChangeAlbumOrder" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <p>Verander de volgorde van het album door de afbeeldingen te slepen:</p>
                <ul class="albumSortable sortable">
                    <li class="menu-item" :id="'image_' + image.id" :data-id="image.id" v-for="image in selectedAlbum.media">
                        <div>
                            <span class="glyphicon glyphicon-move"></span> <img :src="image.thumbnail_url" />
                        </div>
                    </li>
                </ul>
                <p v-if="selectedAlbum.media.length === 0">Er zijn geen afbeeldingen in dit album.</p>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button class="btn btn-success right" v-on:click="updateOrder()">Volgorde opslaan</button>
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
    import AutoloadModal from './AutoloadModal.vue';
    import SelectMedia from './SelectMedia.vue';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                albums: [],
                selectedAlbum: false,
                addImages: false,
                selectedImages: [],
                images: [],
                newAlbum: false,
                newAlbumError: false,
                changeOrder: false,
                per_page: 8,
                current_page: 1,
            };
        },

        components: {
            SelectMedia,
        },

        watch: {
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

            storeImages: function (images) {
                let self = this;
                $.ajax({
                    url: '/cms/albums/'+this.selectedAlbum.id+'/media',
                    type: 'POST',
                    data: {
                        media: images
                    },
                    success: function (data) {
                        self.addImages = false;
                        self.loadAlbums(self.selectedAlbum.id);
                    }
                });
            },

            updateOrder: function() {
                let self = this;
                let sortedArray = $('.albumSortable').nestedSortable('toArray');
                let sorted = [];
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
                        self.changeOrder = false;
                        self.loadAlbums(self.selectedAlbum.id);

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
                let value = $('[name=album]').val();

                if (value == "") {
                    this.newAlbumError = true;
                    alert("Album naam kan niet leeg zijn.");
                    return;
                }

                this.newAlbumError = false;
                this.newAlbum = false;

                let self = this;
                $.ajax({
                    url: '/cms/albums',
                    type: 'POST',
                    data: {
                        name: value,
                        colorbox: 1
                    },
                    success: function(data) {
                        self.albums.push(data);
                        data.media = [];
                        self.selectedAlbum = data;
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

            removeAlbum: function (album) {
                let self = this;
                swal({
                    title: "Album verwijderen?",
                    text: "Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit album",
                }).then(function(){
                    self.doRemoveAlbum(album);
                }).done();
            },

            removeImage: function (album, image) {
                let self = this;
                $.ajax({
                    url: '/cms/albums/'+album.id+'/media/'+image.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function (result) {
                        self.selectedAlbum.media.$remove(image);
                    }
                });
            },

            doRemoveAlbum: function (album) {
                let self = this;
                $.ajax({
                    url: '/cms/albums/'+album.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        self.albums.$remove(album);
                        self.selectedAlbum = _.head(self.albums);
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadAlbums(0);
            },

            loadAlbums: function(selectedAlbumId) {
                let self = this;
                $.get('/cms/albums', function (data) {
                    if (data.length != 0) {
                        self.albums = data;
                        self.selectedAlbum = selectedAlbumId ? _.find(data, ['id', selectedAlbumId]) : _.head(data);
                    }
                });
            },
        },
    }
</script>
