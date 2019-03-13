<template>
    <div>
        <div v-if="this.enableEdit ? selectedImages.length === 0 : true">
            <div class="buttons" style="margin-bottom:15px;">
                <button v-on:click="selectedFolder = false" class="btn btn-primary" v-show="selectedFolder"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp; Ga terug</button>
                <image-filters></image-filters>
            </div>
            <div v-if="!selectedFolder">
                <div class="bootstrap-row media-row" v-for="row in sortedFolders | chunk 6">
                    <div class="col-md-2" v-for="folder in row" :data-id="folder.id">
                        <div class="thumbnail">
                            <a v-on:click="selectedFolder = folder.id">
                                <img src="/folder.png" alt="Folder">
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
                    <img :src="image.thumbnail_url" :id="'select_image_'+image.id" />
                    <span class="glyphicon glyphicon-ok add"></span>
                </div>
            </div>
            <p v-if="sortedImages.length === 0">Er zijn geen afbeeldingen (meer) gevonden.</p>
            <div class="form-group right flex" v-if="enableOpenInPopup">
                <label for="openInPopup" class="control-label" style="margin-right: 10px;">Openen in popup</label>

                <label class="Switch" style="align-self: center;">
                    <input type="checkbox" name="openInPopup" id="openInPopup" v-model="openInPopup">
                    <div class="Switch__slider"></div>
                </label>
            </div>
        </div>
        <div id="jcropEdit" v-else style="margin-bottom: 15px;">
            <p>Klik op de afbeelding om hem bij te snijden.</p>
            <img v-on:click="startEdit()" :src="imagePath(selectedImage.path)" alt="" id="jcropEditImage">
        </div>


        <div class="clear"></div>
        <div class="form-group">
            <div class="col-md-12">
                <button class="btn btn-success right" v-on:click="sendImages()" :disabled="selectedImages.length === 0 || (coordinates.length === 0 && enableEdit)">{{ this.multiple ? `${selectedImages.length} Afbeeldingen toevoegen` : 'Geselecteerde afbeelding gebruiken' }}</button>
                <button class="btn btn-danger right" style="margin-right:5px;"  v-on:click="selectedImages = []" v-if="this.enableEdit && selectedImages.length > 0">Bijsnijden annuleren</button>
                <button class="btn btn-default right" style="margin-right:5px;" v-on:click="cancelSelectImages()">Annuleren</button>
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
    import ImageFilters from './ImageFilters.vue';

    export default {
        props: {
            omitImages: {
                type: Array,
            },
            multiple: {
                type: Boolean,
                default: false,
            },
            enableOpenInPopup: {
                type: Boolean,
                default: false,
            },
            enableEdit: {
                type: Boolean,
                default: false,
            }
        },

        components: {
            ImageFilters,
        },

        data() {
            return {
                folderUrl: '/cms/folders',
                selectedFolder: false,
                openInPopup: false,
                folders: [],
                selectedImages: [],
                allImages: [],
                images: [],
                sortBy: 'created_at',
                order: 'desc',
                coordinates: [],
                zoomFactor: 1,
            }
        },

        computed: {
            sortedImages: function() {
                return _.orderBy(this.selectedFolder ? this.getFolderMedia(this.selectedFolder) : this.images, [image => image[this.sortBy].toLowerCase()], [this.order]);
            },

            sortedFolders: function() {
                return _.orderBy(this.folders, [folder => folder.name.toLowerCase()]);
            },

            selectedImage: function () {
                return this.findImage(this.selectedImages[0]);
            },
        },

        created() {
            this.loadImages();
            this.loadFolders();

            this.$events.$on('setSort', (sortBy, order)  => {
                this.sortBy = sortBy;
                this.order = order;
            });
        },

        methods: {
            loadImages: function() {
                let self = this;
                $.get('/cms/media?filterFolder=true', function (data) {
                    self.images = self.filterImages(data);
                });

                $.get('/cms/media', function (data) {
                    self.allImages = self.filterImages(data);
                })
            },

            loadFolders: function() {
                let self = this;
                $.get(this.folderUrl, function (data) {
                    self.folders = data;
                });
            },

            imagePath: function(path) {
                return '/'+path;
            },

            sendImages: function() {
                if (this.multiple) {
                    this.$emit('send-images', this.selectedImages);
                } else {
                    this.$emit('send-image', this.selectedImage, this.openInPopup, this.coordinates);
                }

                this.selectedImages = [];
            },

            cancelSelectImages: function() {
                this.$emit('cancel');
            },

            selectImage: function (image) {
                if (!this.multiple) {
                    this.selectedImages = [];
                }

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

            findImage: function (id) {
                return _.find(this.allImages, ['id', id])
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
        }
    }
</script>