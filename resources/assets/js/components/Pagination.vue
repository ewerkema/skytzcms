<template>
    <nav class="flex-center" aria-label="Page navigation">
        <div class="media-pagination">
            <ul class="pagination">
                <li :class="{ 'disabled': current_page === 1 }">
                    <a href="#" aria-label="Previous" v-on:click="goTo(prev_page)" v-if="current_page !== 1">
                        <span class="visible-xs"><</span>
                        <span class="hidden-xs">Vorige</span>
                    </a>
                    <div v-else>
                        <span class="visible-xs"><</span>
                        <span class="hidden-xs">Vorige</span>
                    </div>
                </li>
                <li v-for="page in shortPages()" :class="{ 'active': current_page === page}">
                    <a href="#"
                       v-if="current_page !== page"
                       v-on:click="goTo(page)">
                        {{ page }}
                    </a>
                    <span v-else>
                        {{ page }}
                    </span>
                </li>
                <li :class="{ 'disabled': current_page === last_page }">
                    <a href="#" aria-label="Next" v-on:click="goTo(next_page)" v-if="current_page !== last_page">
                        <span class="hidden-xs">Volgende</span>
                        <span class="visible-xs">></span>
                    </a>
                    <div v-else>
                        <span class="hidden-xs">Volgende</span>
                        <span class="visible-xs">></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        props: [ 'per_page', 'total', 'current_page'],

        watch: {
            last_page: function (value) {
                if (value < this.current_page) {
                    this.$events.$emit('resetCurrentPage');
                }
            },
        },

        computed: {
            to: function() {
                return Math.min(this.current_page * this.per_page, this.total);
            },
            from: function() {
                return Math.min(this.total, (this.current_page - 1) * this.per_page + 1);
            },
            last_page: function() {
                return Math.max(1, Math.ceil(this.total / this.per_page));
            },
            next_page: function() {
                return Math.min(this.current_page+1, this.last_page);
            },
            prev_page: function() {
                return Math.max(1, this.current_page-1);
            },
        },

        methods: {
            goTo: function(page) {
                this.$events.$emit('changePage', page);
            },

            shortPages: function() {
                if (this.last_page < 6)
                    return _.range(1, this.last_page+1);

                let seperator = '..';
                let pages = [1];

                if (this.current_page > 1 && this.current_page <= this.last_page - 1) {
                    if (this.current_page > 3)
                        pages.push(seperator);

                    if (this.current_page > 2)
                        pages.push(this.current_page - 1);

                    pages.push(this.current_page);

                    if (this.current_page <= this.last_page - 2)
                        pages.push(this.current_page + 1);

                    if (this.current_page <= this.last_page - 3)
                        pages.push(seperator);
                } else {
                    pages = pages.concat([2, seperator, this.last_page - 1]);
                }
                pages.push(this.last_page);

                return pages;
            }
        }
    }
</script>