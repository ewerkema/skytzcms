<template>
    <div>
        <select-media :enable-open-in-popup="true" :enable-edit="enableEdit" @cancel="$(this.target).modal('close')" @send-image="sendImage"></select-media>
        <img id="replace_image" src="" alt="" class="hidden">
    </div>
</template>

<script>
    import SelectMedia from './SelectMedia.vue';

    export default {
        props: {
            target: {
                type: String,
                default: "",
            },

            enableEdit: {
                type: Boolean,
                default: false,
            }
        },

        components: {
            SelectMedia,
        },

        methods: {
            sendImage: function(image, openInPopup, coordinates) {
                if (this.enableEdit) {
                    $.post('/cms/media/' + image.id + '/header', coordinates).error(function () {
                        alert("Er ging iets fout bij het bijsnijden van de foto, neem contact op met de admin!")
                    });
                }

                let imageEl = document.getElementById('replace_image');
                imageEl.src = this.imagePath(image.path);
                if (window.parent.CustomMediaManager !== undefined && window.parent.CustomMediaManager.active) {
                    window.parent.CustomMediaManager._insertImage(this.imagePath(image.path), imageEl.naturalWidth, imageEl.naturalHeight, openInPopup);
                } else {
                    $('.selected_media_id').val(image.id).trigger('change');
                    $('.selected_media_name').val(image.name);
                }

                $(this.target).modal('hide');
            },

            imagePath: function(path) {
                return '/'+path;
            },
        },
    }
</script>
