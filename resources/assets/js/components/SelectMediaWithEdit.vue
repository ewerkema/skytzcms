<template>
    <div class="select-media-with-edit">
        <div class="selectMedia" v-if="!selectedImage">
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
        <div id="jcropEdit" v-else>
            <p>Klik op de afbeelding om hem bij te snijden. {{ coordinates | json}}</p>
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
    import Pagination from "./Pagination.vue";
    import VueEvents from 'vue-events';
    Vue.use(VueEvents);

    export default {
        data(){
            return {
                selectedImage: false,
                selectedPage: 0,
                totalPages: 0,
                per_page: 8,
                current_page: 1,
                coordinates: [],
                images: [],
                zoomFactor: 1,
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
            this.loadFromDatabase();
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
