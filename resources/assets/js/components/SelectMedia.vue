<template>
    <div>
        <div class="selectMedia">
            <div class="bootstrap-row album-row">
                <div class="album-image"
                     v-for="image in currentImages"
                     v-on:click="selectImage(image)"
                     :class="[{selected: image.id == selectedImage.id }, 'col-md-3']"
                >
                    <img :src="imagePath(image.path)" :id="'select_image_'+image.id" />
                    <span class="glyphicon glyphicon-ok add"></span>
                </div>
            </div>
            <p v-if="images.length == 0">Er zijn geen afbeeldingen gevonden.</p>

            <pagination :total="images.length" :per_page="per_page" :current_page="current_page"></pagination>
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
    import Pagination from "./Pagination.vue";
    import VueEvents from 'vue-events';
    import ListBase from './ListBase.vue';
    Vue.use(VueEvents);

    export default {
        extends: ListBase,

        data(){
            return {
                selectedImage: false,
                openInPopup: false,
                selectedPage: 0,
                totalPages: 0,
                per_page: 8,
                current_page: 1,
                images: []
            };
        },

        components: {
            Pagination,
        },

        computed: {
            total: function() {
                return this.images.length;
            },

            to: function() {
                return Math.min(this.current_page * this.per_page, this.total);
            },

            from: function() {
                return Math.min(this.total, (this.current_page - 1) * this.per_page + 1);
            },

            currentImages: function() {
                return this.images.slice(
                    Math.min((this.current_page - 1) * this.per_page, this.images.length),
                    this.current_page * this.per_page
                );
            }
        },

        filters: {
            limitPage: function(arr) {
                var imagesPerPage = this.imagesPerRow * 2;
                return arr.slice(this.selectedPage * imagesPerPage, (this.selectedPage + 1) * imagesPerPage);
            }
        },

        created() {
            this.$events.$on('changePage', page => this.changePage(page));
            this.$events.$on('resetCurrentPage', () => this.changePage(1));
        },

        watch: {
            images: function (data) {
               this.totalPages = Math.floor(data.length / (this.imagesPerRow * 2));
            }
        },

        methods: {
            changePage: function (page) {
                this.current_page = page;
            },

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

    }
</script>
