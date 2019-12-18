<template>

</template>

<script>
    export default {
        props: ['target'],

        mounted() {
            let self = this;
            $(this.target).on('shown.bs.modal', function (e) {
                self.loadFromDatabase();
            });
        },

        methods: {
            loadFromDatabase: function () {},
        }
    }
</script>
