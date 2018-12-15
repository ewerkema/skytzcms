<template>
    <div>
        <button v-on:click="selectedFolder = false" class="btn btn-primary" v-show="selectedFolder" style="margin-bottom: 15px;"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Ga terug</button>
        <div class="flex-row" v-if="!selectedFolder">
            <div class="item" v-for="folder in sortedFolders" :data-id="folder.id">
                <div class="thumbnail">
                    <a v-on:click="selectedFolder = folder.id">
                        <img :src="'../folder.png'" alt="Folder">
                    </a>
                    <p>{{ folder.name }}</p>
                </div>
            </div>
        </div>
        <div class="selectMedia">
            <div class="bootstrap-row album-row">
                <div class="album-image"
                     v-for="image in sortedImages"
                     v-on:click="selectImage(image)"
                     :class="[{selected: image.id == selectedImage.id }, 'col-md-1']"
                >

                    <img :src="imagePath(image.path)" :id="'select_image_'+image.id" />
                    <span class="glyphicon glyphicon-ok add"></span>
                </div>
            </div>
            <p v-if="images.length == 0">Er zijn geen afbeeldingen gevonden.</p>
        </div>
        <div class="form-group right flex">
            <label for="openInPopup" class="control-label" style="margin-right: 10px;">Openen in popup</label>

            <label class="Switch" style="align-self: center;">
                <input type="checkbox" name="openInPopup" id="openInPopup" v-model="openInPopup">
                <div class="Switch__slider"></div>
            </label>
        </div>
        <div class="clear"></div>
        <button type="button" class="btn btn-success right" @click="sendImage()" :disabled="!selectedImage">Geselecteerde afbeelding gebruiken</button>
        <div class="clear"></div>
    </div>
</template>
<style>
    .album-row {
        flex-wrap: wrap;
        -webkit-flex-wrap: wrap;
    }

    .album-row .album-image {
        margin-bottom: 15px;
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
                openInPopup: false,
                selectedPage: 0,
                totalPages: 0,
                per_page: 8,
                current_page: 1,
                images: [],
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

            loadFromDatabase: function() {
                this.loadImages();
                this.loadFolders();
            },

            sendImage: function() {
                let image = document.getElementById('select_image_'+this.selectedImage.id);
                if (window.parent.CustomMediaManager !== undefined && window.parent.CustomMediaManager.active) {
                    window.parent.CustomMediaManager._insertImage(this.imagePath(this.selectedImage.path), image.naturalWidth, image.naturalHeight, this.openInPopup);
                } else {
                    $('.selected_media_id').val(this.selectedImage.id).trigger('change');
                    $('.selected_media_name').val(this.selectedImage.name);
                }

                this.selectedImage = false;
                $('#selectMediaModal').modal('toggle');
            },

            loadImages: function() {
                let self = this;
                $.get('/cms/media?filterFolder=true', function (data) {
                     self.images = self.filterImages(data);
                });
            },

            loadFolders: function() {
                let self = this;
                $.get(this.folderUrl, function (data) {
                    self.folders = data;
                });
            },

            isImage: function (image) {
                let extensions = ['jpg', 'png', 'tif', 'jpeg', 'gif'];
                return _.includes(extensions, image.extension.toLowerCase());
            },

            filterImages: function (images) {
                let self = this;
                return _.filter(images, function (image) {
                    return self.isImage(image);
                });
            },

            getFolderMedia: function (folderId) {
                let index = _.findIndex(this.folders, folder => folder.id === folderId);

                return index !== -1 ? this.folders[index].media : [];
            },

        },

    }
</script>
