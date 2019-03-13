<template>
    <div>
        <button v-on:click="selectedFolder = false" class="btn btn-primary" v-show="selectedFolder" style="margin-bottom: 15px;"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Ga terug</button>
        <div v-if="!selectedFolder">
            <div class="bootstrap-row media-row" v-for="row in sortedFolders | chunk 6">
                <div class="col-md-2" v-for="folder in row" :data-id="folder.id">
                    <div class="thumbnail">
                        <a v-on:click="selectedFolder = folder.id">
                            <img :src="'../folder.png'" alt="Folder">
                        </a>
                        <p>{{ folder.name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bootstrap-row media-row" v-for="row in sortedImages | chunk 6">
            <div class="col-md-2 slider-image"
                 v-for="image in row"
                 v-on:click="selectImage(image)"
                 :class="{selected: isSelected(image) }"
            >
                <img :src="image.thumbnail_url" />
                <span class="glyphicon glyphicon-ok add"></span>
            </div>
        </div>
        <p v-if="sortedImages.length === 0">Er zijn geen afbeeldingen (meer) gevonden.</p>
        <div class="form-group">
            <div class="col-md-8 col-md-offset-3">
                <button class="btn btn-success right" v-on:click="sendImages()">{{ selectedImages.length }} Afbeeldingen toevoegen</button>
                <button class="btn btn-default right" v-on:click="cancelSelectImages()">Annuleren</button>
            </div>
        </div>
    </div>
</template>

<style>
    .media-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
</style>

<script>
    import VueEvents from 'vue-events';
    Vue.use(VueEvents);

    export default {
        props: ['omitImages'],

        data() {
            return {
                folderUrl: '/cms/folders',
                selectedFolder: false,
                folders: [],
                selectedImages: [],
                images: [],
            }
        },

        computed: {
            sortedImages: function() {
                return _.orderBy(this.selectedFolder ? this.getFolderMedia(this.selectedFolder) : this.images, [image => image.name.toLowerCase()]);
            },

            sortedFolders: function() {
                return _.orderBy(this.folders, [folder => folder.name.toLowerCase()]);
            }
        },

        created() {
            this.loadImages();
            this.loadFolders();
        },

        methods: {
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

            sendImages: function() {
                this.$events.$emit('sendImages', this.selectedImages)
            },

            cancelSelectImages: function() {
                this.$events.$emit('cancelSelectImages');
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

            isImage: function (image) {
                let extensions = ['jpg', 'png', 'tif', 'jpeg', 'gif'];
                return _.includes(extensions, image.extension.toLowerCase());
            },

            imageShouldBeOmitted: function (image) {
                return _.find(this.omitImages, ['id', image.id]);
            },

            filterImages: function (images) {
                let self = this;
                return _.filter(images, function (image) {
                    return self.isImage(image) && !self.imageShouldBeOmitted(image);
                });
            },

            getFolderMedia: function (folderId) {
                let index = _.findIndex(this.folders, folder => folder.id === folderId);

                return index !== -1 ? this.filterImages(this.folders[index].media) : [];
            },
        }
    }
</script>