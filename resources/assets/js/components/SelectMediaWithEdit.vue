<template>
    <div class="select-media-with-edit">
        <button v-on:click="selectedFolder = false" class="btn btn-primary" v-show="selectedFolder && !selectedImage" style="margin-bottom: 15px;"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Ga terug</button>
        <div class="flex-row" v-if="!selectedFolder && !selectedImage">
            <div class="item" v-for="folder in sortedFolders" :data-id="folder.id">
                <div class="thumbnail">
                    <a v-on:click="selectedFolder = folder.id">
                        <img :src="'../folder.png'" alt="Folder">
                    </a>
                    <p>{{ folder.name }}</p>
                </div>
            </div>
        </div>
        <div class="selectMedia" v-if="!selectedImage">
            <div class="bootstrap-row album-row">
                <div class="album-image"
                     v-for="image in sortedImages"
                     v-on:click="selectImage(image)"
                     :class="[{selected: image.id == selectedImage.id }, 'col-md-2']"
                >
                    <img :src="image.thumbnail_url" :id="'select_image_'+image.id" />
                    <span class="glyphicon glyphicon-ok add"></span>
                </div>
            </div>
            <p v-if="images.length == 0">Er zijn geen afbeeldingen gevonden.</p>
        </div>
        <div id="jcropEdit" v-else>
            <p>Klik op de afbeelding om hem bij te snijden.</p>
            <img v-on:click="startEdit()" :src="imagePath(selectedImage.path)" alt="" id="jcropEditImage">
        </div>

        <div class="clear"></div>
        <button type="button" class="btn btn-success right" @click="sendImage()" :disabled="!selectedImage || coordinates.length === 0">Geselecteerde afbeelding gebruiken</button>
        <button class="btn btn-default right" v-on:click.prevent="selectedImage = false">Annuleren</button>
        <div class="clear"></div>
    </div>
</template>
<style>
    .album-row {
        flex-wrap: wrap;
        -webkit-flex-wrap: wrap;
    }

    .album-row .album-image, #jcropEdit {
        margin-bottom: 15px;
    }

    .select-media-with-edit button {
        margin-left: 15px;
    }

    #jcropEditImage {
        margin: 0;
    }
</style>
<script>
    import VueEvents from 'vue-events';
    import ListBase from './ListBase.vue';
    Vue.use(VueEvents);


    export default {
        extends: ListBase,

        data(){
            return {
                folderUrl: '/cms/folders',
                selectedImage: false,
                selectedPage: 0,
                totalPages: 0,
                per_page: 8,
                current_page: 1,
                coordinates: [],
                images: [],
                zoomFactor: 1,
                selectedFolder: false,
                folders: [],
            };
        },

        computed: {
            sortedImages: function() {
                return _.orderBy(this.selectedFolder ? this.getFolderMedia(this.selectedFolder) : this.images, [image => image.name.toLowerCase()]);
            },

            sortedFolders: function() {
                return _.orderBy(this.folders, [folder => folder.name.toLowerCase()]);
            }
        },

        methods: {
            imagePath: function(path) {
                return '/'+path;
            },

            selectImage: function (image) {
                this.$set('selectedImage', image);
            },

            startEdit: function() {
                let img = event.target;
                this.zoomFactor = img.naturalWidth / img.width;

                $(event.target).Jcrop({
                    onChange: this.updateCoordinates,
                    aspectRatio: window.headerWidth / window.headerHeight,
                    minSize: [
                        Math.min(window.headerWidth / this.zoomFactor, img.naturalWidth),
                        Math.min(window.headerWidth / this.zoomFactor, img.naturalHeight),
                    ],
                });
            },

            updateCoordinates: function(c) {
                this.coordinates = {
                    x: c.x * this.zoomFactor,
                    y: c.y * this.zoomFactor,
                    x2: c.x2 * this.zoomFactor,
                    y2: c.y2 * this.zoomFactor,
                    w: c.w * this.zoomFactor,
                    h: c.h * this.zoomFactor,
                };
            },

            loadFromDatabase: function() {
                this.loadImages();
                this.loadFolders();
            },

            sendImage: function() {
                let self = this;
                $.post('/cms/media/' + this.selectedImage.id + '/header', this.coordinates)
                .done(function(image) {
                    $('.selected_media_id').val(image.id).trigger('change');
                    $('.selected_media_name').val(image.name);

                    self.selectedImage = false;
                    $('#selectMediaWithEditModal').modal('toggle');
                })
                .error(function() {
                    alert("Er ging iets fout bij het bijsnijden van de foto, neem contact op met de admin!")
                });
            },

            loadImages: function() {
                var _this = this;
                $.get('/cms/media', function (data) {
                     _this.images = _this.filterImages(data);
                });
            },

            loadFolders: function() {
                let self = this;
                $.get(this.folderUrl, function (data) {
                    self.folders = data;
                });
            },

            isImage: function (image) {
                var extensions = ['jpg', 'png', 'tif', 'jpeg', 'gif'];
                return _.includes(extensions, image.extension.toLowerCase());
            },

            filterImages: function (images) {
                var _this = this;
                return _.filter(images, function (image) {
                    return _this.isImage(image);
                });
            },

            getFolderMedia: function (folderId) {
                let index = _.findIndex(this.folders, folder => folder.id === folderId);

                return index !== -1 ? this.folders[index].media : [];
            },

        },

    }
</script>
