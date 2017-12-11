<template>
    <div class="selectMedia" class="col-md-12">
        <div class="bootstrap-row album-row" v-for="row in images | limitPage | chunk imagesPerRow">
            <div class="album-image"
                 v-for="image in row"
                 v-on:click="selectImage(image)"
                 :class="[{selected: image.id == selectedImage.id }, 'col-md-' + Math.floor(12/imagesPerRow)]"
            >
                <img :src="imagePath(image.path)" id="select_image_{{ image.id }}" />
                <span class="glyphicon glyphicon-ok add"></span>
            </div>
        </div>
        <p v-if="images.length == 0">Er zijn geen afbeeldingen gevonden.</p>

        <nav class="flex-center" aria-label="Select media navigation">
            <div class="media-pagination">
                <ul class="pagination">
                    <li :class="{disabled: selectedPage == 0}">
                        <span v-if="selectedPage == 0">«</span>
                        <a v-else href="#" rel="prev" @click="selectedPage = selectedPage - 1">«</a>
                    </li>
                    <li v-for="p in (totalPages+1)" :class="{active: selectedPage == p}">
                        <span v-if="selectedPage == p">{{ p+1 }}</span>
                        <a v-else href="#" @click="selectedPage = p">{{ p+1 }}</a>
                    </li>
                    <li :class="{disabled: selectedPage == totalPages}">
                        <span v-if="selectedPage == totalPages">»</span>
                        <a v-else href="#" rel="next" @click="selectedPage = selectedPage + 1">»</a>
                    </li>
                </ul>
            </div>
        </nav>
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
</template>
<style>

</style>
<script>
    export default {
        data(){
            return {
                selectedImage: false,
                openInPopup: false,
                selectedPage: 0,
                totalPages: 0,
                imagesPerRow: 4,
                images: []
            };
        },

        created() {
            this.loadFromDatabase();
        },

        watch: {
            images: function (data) {
               this.totalPages = Math.floor(data.length / (this.imagesPerRow * 2));
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
            },

            sendImage: function() {
                var image = document.getElementById('select_image_'+this.selectedImage.id);
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
                var _this = this;
                $.get('/cms/media', function (data) {
                     _this.images = _this.filterImages(data);
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
            }

        },

        filters: {
            limitPage: function(arr) {
                var imagesPerPage = this.imagesPerRow * 2;
                return arr.slice(this.selectedPage * imagesPerPage, (this.selectedPage + 1) * imagesPerPage);
            }
        },

        computed: {

        }
    }
</script>
