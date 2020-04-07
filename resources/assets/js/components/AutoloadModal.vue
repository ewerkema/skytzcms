<template>

</template>

<script>
    export default {
        props: ['target'],

        mounted() {
            let self = this;
            $(this.target).on('shown.bs.modal', function (e) {
                self.load();
            });
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
            }
        }
    }
</script>
