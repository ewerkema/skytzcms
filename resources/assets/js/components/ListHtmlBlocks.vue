<template>
    <div class='list-html-blocks'>
        <div class="sidebar col-md-4">
            <ul class="list-group">
                <a href="#" class="list-group-item"
                   v-for="htmlBlock in htmlBlocks"
                   :class="{ active: (htmlBlock.id == selectedHtmlBlock) }"
                   v-on:click="selectedHtmlBlock = htmlBlock.id"
                >
                    {{ htmlBlock.name }}
                </a>
                <a href="#" class="list-group-item add-item"
                   v-on:click="newHtmlBlock = true"
                   v-if="!newHtmlBlock"
                >
                    Voeg html blok toe
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
                <a href="#" class="list-group-item add-item"
                   v-if="newHtmlBlock"
                   :class="{ 'has-error': newHtmlBlockError }"
                >
                    <input type="text" name="html_block_name" class="form-control" @keyup.enter="createHtmlBlock()" placeholder="Html blok naam" />
                    <button class="btn btn-default" v-on:click="newHtmlBlock = false">Annuleren</button>
                    <button class="btn btn-success right" v-on:click="createHtmlBlock()">Opslaan</button>
                </a>
            </ul>
        </div>
        <div class="col-md-8" v-if="selectedHtmlBlock && getHtmlBlock(selectedHtmlBlock)">
            <form action="#" class="form-horizontal editForm" id="htmlBlockForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" id="name" name="name" :value="getHtmlBlock(selectedHtmlBlock).name" class="form-control" placeholder="Naam" required autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="html" class="col-md-3 control-label">HTML</label>

                    <div class="col-md-8">
                        <textarea id="html" name="html" placeholder="HTML blok" required autofocus>{{ getHtmlBlock(selectedHtmlBlock).html }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="htmlBlockForm" class="btn btn-success right" v-on:click="submitForm()">Html blok opslaan</button>
                        <button class="btn btn-danger right" v-on:click="removeHtmlBlock(selectedHtmlBlock)">Verwijder html blok</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8" v-else>
            <p>Er is geen html blok geselecteerd.</p>
        </div>
    </div>
</template>
<style>
    .add-item > .glyphicon {
        float: right;
        margin-right: 4px;
    }

    .table .right {
        float: right;
    }

    .editForm button {
        margin-left: 15px;
    }

    .editForm textarea {
        border-radius: 4px !important;
    }

    .list-articles .btn.right {
        margin-left: 15px;
    }
</style>
<script>
    import AutoloadModal from './AutoloadModal.vue';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                htmlBlocks: [],
                selectedHtmlBlock: false,
                newHtmlBlock: false,
                newHtmlBlockError: false
            };
        },

        methods: {
            getHtmlBlock: function(htmlBlockId) {
                return _.find(this.htmlBlocks, ['id', htmlBlockId]);
            },

            submitForm: function () {
                let request = new Request('/cms/htmlBlocks');
                request.setForm('#htmlBlockForm');
                request.setType('PATCH');
                request.addToUrl(this.selectedHtmlBlock);

                request.addFields(['html', 'name']);

                let self = this;
                request.send(function(data) {
                    self.htmlBlocks[_.findIndex(self.htmlBlocks, (htmlBlock) => htmlBlock.id === data.id)] = data;
                    swal({
                        title: "Html blok aangepast!",
                        text: 'Html blok is succesvol aangepast.',
                        type: "success",
                        timer: 2000
                    }).done();
                });
            },

            createHtmlBlock: function() {
                let value = $('[name=html_block_name]').val();

                if (value == "") {
                    this.newHtmlBlockError = true;
                    alert("Html blok naam kan niet leeg zijn.");
                    return;
                }

                this.newHtmlBlockError = false;
                this.newHtmlBlock = false;

                let self = this;
                $.ajax({
                    url: '/cms/htmlBlocks',
                    type: 'POST',
                    data: { name: value },
                    success: function(data) {
                        self.htmlBlocks.push(data);
                        self.selectedHtmlBlock = data.id;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            removeHtmlBlock: function (htmlBlockId) {
                let self = this;
                swal({
                    title: "Html block verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit blok",
                }).then(function(){
                    self.doRemoveHtmlBlock(htmlBlockId);
                }).done();
            },

            doRemoveHtmlBlock: function (htmlBlockId) {
                let self = this;
                $.ajax({
                    url: '/cms/htmlBlocks/'+htmlBlockId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = _.findIndex(self.htmlBlocks, htmlBlock => htmlBlock.id === htmlBlockId);
                        self.htmlBlocks.splice(index, 1);
                        self.selectedHtmlBlock = (_.head(self.htmlBlocks) !== undefined) ? _.head(self.htmlBlocks).id : false;
                    }
                });
            },

            loadFromDatabase: function() {
                return this.loadHtmlBlocks();
            },

            loadHtmlBlocks: function() {
                let self = this;
                return $.get('/cms/htmlBlocks', function (data) {
                    if (data.length != 0) {
                        self.htmlBlocks = data;
                        self.selectedHtmlBlock = _.head(data).id;
                    }
                });
            }

        },

        filters: {
            truncate: function(string, value) {
                return (string.length > value) ? string.substring(0, value) + '...' : string;
            }
        }
    }
</script>
