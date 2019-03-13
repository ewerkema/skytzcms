<template>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-sort"></span> Sorteren op <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" @click.prevent="setSort('name', 'asc')">Alfabetisch (A-Z)</a></li>
            <li><a href="#" @click.prevent="setSort('name', 'desc')">Alfabetisch (Z-A)</a></li>
            <li><a href="#" @click.prevent="setSort('created_at', 'desc')">Aangemaakt op (nieuwste bovenaan)</a></li>
            <li><a href="#" @click.prevent="setSort('created_at', 'asc')">Aangemaakt op (oudste bovenaan)</a></li>
        </ul>
    </div>
</template>

<script>
    import VueEvents from 'vue-events';
    Vue.use(VueEvents);

    export default {
        created() {
            this.setSort('created_at', 'desc')
        },

        methods: {
            setSort: function(sortBy, order) {
                this.$events.$emit('setSort', sortBy, order);
            }
        }
    }
</script>