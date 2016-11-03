<template>
    <div class="selectMedia" class="col-md-12">
        <div class="bootstrap-row album-row" v-for="row in images | chunk 6">
            <div class="col-md-2 album-image"
                 v-for="image in row"
                 v-on:click="selectImage(image)"
                 :class="{selected: image.id == selectedImage.id }"
            >
                <img :src="imagePath(image.path)" id="select_image_{{ image.id }}" />
                <span class="glyphicon glyphicon-ok add"></span>
            </div>
        </div>
        <p v-if="images.length == 0">Er zijn geen afbeeldingen gevonden.</p>
    </div>
    <button type="button" class="btn btn-success right" @click="sendContentTools()" :disabled="!selectedImage">Geselecteerde afbeelding invoegen</button>
    <div class="clear"></div>
</template>
<style>

</style>
<script>
    export default {
        data(){
            return {
                selectedImage: false,
                images: []
            };
        },

        created() {
            this.loadFromDatabase();
        },

        watch: {

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

            sendContentTools: function() {
                var image = document.getElementById('select_image_'+this.selectedImage.id);
                console.log('Width:'+image.naturalWidth+' height:'+image.naturalHeight)
                window.parent.CustomMediaManager._insertImage(this.imagePath(this.selectedImage.path), image.naturalWidth, image.naturalHeight);
                this.selectedImage = false;
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

        computed: {

        }
    }
</script>
