<template>
    <div>
        <select-media :enable-open-in-popup="true" :enable-edit="enableEdit" :target="target" @cancel="closeModal" @send-image="sendImage"></select-media>
        <img id="replace_image" src="//:0" alt="" style="visibility: hidden">
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
            sendImage: function(image, openInPopup, coordinates, edit = true) {
                if (this.enableEdit && edit) {
                    let self = this;
                    $.post('/cms/media/' + image.id + '/header', coordinates)
                        .done(function (image) {
                            self.updateImage(image, openInPopup);
                        })
                        .error(function () {
                            alert("Er ging iets fout bij het bijsnijden van de foto, neem contact op met de admin!")
                        });
                } else {
                    let replaceImage = '#replace_image';
                    if (window.parent.CustomMediaManager !== undefined && window.parent.CustomMediaManager.active) {
                        $(replaceImage)
                            .attr('src', this.imagePath(image.path))
                            .unbind()
                            .load(() => {
                                window.parent.CustomMediaManager._insertImage(this.imagePath(image.path), $(replaceImage).get(0).naturalWidth, $(replaceImage).get(0).naturalHeight, openInPopup);
                            });
                    } else {
                        this.updateImage(image, openInPopup);
                    }
                }

                this.closeModal();
            },

            updateImage: function(image, openInPopup) {
                $('.selected_media_id').val(image.id).trigger('change');
                $('.selected_media_name').val(image.name);
                this.$root.$emit('insert-image', image, openInPopup);
            },

            imagePath: function(path) {
                return '/'+path;
            },

            closeModal: function() {
                $(this.target).modal('hide');
            }
        },
    }
</script>
