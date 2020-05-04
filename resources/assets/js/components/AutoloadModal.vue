<template>

</template>

<script>
    export default {
        props: ['target'],

        mounted() {
            $(this.target).on('shown.bs.modal', (e) => {
                $(document).off('focusin.modal');
                this.onOpen(e);
                this.load();
            });
            $(this.target).on('hidden.bs.modal', (e) => this.onClose(e))
        },

        methods: {
            load: function() {
                this.showSpinner();
                let promise = this.loadFromDatabase();
                promise.always(this.hideSpinner);
            },

            loadFromDatabase: function () {},

            showSpinner: function() {
                $(this.target + ' .spinner').show();
            },

            hideSpinner: function() {
                $(this.target + ' .spinner').hide();
            },

            onOpen: function (e) {

            },

            onClose: function (e) {

            }
        }
    }
</script>
