<template>
    <div>
        <div v-if="!editMode()" class='list-articles'>
            <div class="sidebar col-md-4">
                <ul class="list-group">
                    <a href="#" class="list-group-item"
                       v-for="articleGroup in articleGroups"
                       :class="{ active: (articleGroup.id == selectedArticleGroup.id) }"
                       v-on:click="selectedArticleGroup = articleGroup"
                    >
                        <span class="badge">{{ countArticles(articleGroup) }}</span>
                        {{ articleGroup.title }}
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-on:click="newArticleGroup = true"
                       v-if="!newArticleGroup"
                    >
                        Voeg nieuwsgroep toe
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-if="newArticleGroup"
                       :class="{ 'has-error': newArticleGroupError }"
                    >
                        <input type="text" name="article_group" class="form-control" @keyup.enter="createArticleGroup()" placeholder="Nieuwsgroep naam" />
                        <button class="btn btn-default" v-on:click="newArticleGroup = false">Annuleren</button>
                        <button class="btn btn-success right" v-on:click="createArticleGroup()">Opslaan</button>
                    </a>
                </ul>
            </div>
            <div class="col-md-8" v-if="selectedArticleGroup">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Titel</th>
                        <th>Publiceer datum</th>
                        <th></th>
                    </tr>
                    <tr v-for="(article, i) in selectedArticles">
                        <td>{{ i+1 }}</td>
                        <td>{{ article.title | truncate(30) }}</td>
                        <td>{{ article.created_at | moment("dddd, D MMMM YYYY") | capitalize }}</td>
                        <td>
                            <a href="#" v-on:click="editArticle(article)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" v-on:click="removeArticle(article)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!selectedArticles.length">
                        <td colspan="4">Er zijn geen nieuwsberichten gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" v-on:click="createArticle()">Nieuw artikel</button>
                <button class="btn btn-danger right" v-on:click="removeArticleGroup(selectedArticleGroup)">Verwijder nieuwsgroep</button>
            </div>
            <div class="col-md-8" v-else>
                <p>Er is geen nieuwsgroep geselecteerd.</p>
            </div>
        </div>
        <div class="editForm" v-if="editMode()">
            <form action="#" class="form-horizontal" id="articleForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="article_group_id" class="col-md-3 control-label">Nieuwsgroep</label>

                    <div class="col-md-8">
                        <select class="form-control" id="article_group_id" name="article_group_id">
                            <option value="" :selected="!selectedArticle.article_group_id" disabled>Selecteer een nieuwsgroep</option>
                            <option v-for="articleGroup in articleGroups" :value="articleGroup.id" :selected="selectedArticle.article_group_id == articleGroup.id">
                                {{ articleGroup.title }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Titel</label>

                    <div class="col-md-8">
                        <input type="text" id="title" name="title" v-model="selectedArticle.title" class="form-control" placeholder="Titel" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="summary" class="col-md-3 control-label">Introductie</label>

                    <div class="col-md-8">
                        <textarea type="text" id="summary" name="summary" placeholder="Introductie" v-model="selectedArticle.summary"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image_id" class="col-md-3 control-label">Artikel afbeelding</label>

                    <div class="col-md-8">
                        <div class="input-group input-pointer">
                            <input type="hidden" name="image_id" id="image_id" v-model="selectedArticle.image_id" class="form-control" />
                            <span class="input-group-addon" id="media-picture" @click="$root.selectMedia()"><span class="glyphicon glyphicon-picture"></span></span>
                            <input type="text" name="image_name" @click="$root.selectMedia()" v-model="selectedArticle.image_name" class="form-control no-border-radius" placeholder="Artikel afbeelding" />
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" @click="removeMedia()"><span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="body" class="col-md-3 control-label">Artikel</label>

                    <div class="col-md-8">
                        <vue-editor @text-change="onTextChange" v-model="content"></vue-editor>
                        <input type="hidden" name="body" id="body" v-model="selectedArticle.body">
                    </div>
                </div>
                <div class="form-group">
                    <label for="published" class="col-md-3 control-label">Gepubliceerd</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="published" id="published" v-model="selectedArticle.published">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="articleForm" class="btn btn-success right" v-on:click="submitForm()">Artikel opslaan</button>
                        <button class="btn btn-default right" v-on:click="cancelEdit($event)">Annuleren</button>
                    </div>
                </div>
            </form>
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
    import { VueEditor } from 'vue2-editor';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                articleGroups: [],
                articles: [],
                selectedArticleGroup: {},
                selectedArticles: [],
                selectedArticle: {},
                newArticleGroup: false,
                newArticleGroupError: false,
                selectedArticleImageName: "",
                content: '',
            };
        },

        components: { VueEditor },

        watch: {
            selectedArticleGroup: function (articleGroup) {
                this.selectedArticles = this.getSelectedArticles(articleGroup);
            },

            articles: function (val) {
                this.selectedArticles = this.getSelectedArticles(this.selectedArticleGroup);
            },

            selectedArticle: function (val) {
                if (!this.editMode()) return;

                if (val.image_id) {
                    let self = this;
                    $.ajax({
                        url: '/cms/media/'+val.image_id,
                        type: 'GET',
                        success: function(data) {
                            self.selectedArticle.image_name = data.name;
                            self.$forceUpdate();
                        },
                        error: function() {
                            alert("Er ging iets fout, probeer het later opnieuw");
                        }
                    });

                } else {
                    this.selectedArticle.image_name = "";
                }
            }
        },

        mounted() {
            this.$root.$on('insert-image', (image) => {
                this.selectedArticle.image_id = image.id;
                this.selectedArticle.image_name = image.name;
            });
        },

        methods: {
            countArticles: function (articleGroup) {
                return this.getSelectedArticles(articleGroup).length;
            },

            getSelectedArticles: function (articleGroup) {
                return _.filter(this.articles, ['article_group_id', articleGroup.id]);
            },

            editMode: function() {
                return !_.isEmpty(this.selectedArticle);
            },

            submitForm: function () {
                let request = new Request('/cms/articles');
                request.setForm('#articleForm');
                request.setType('POST');

                request.addFields(['article_group_id', 'title', 'summary', 'body', 'published', 'image_id']);
                request.addCheckboxes(['published']);

                if (this.selectedArticle.id != undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedArticle.id);
                }

                let self = this;
                request.send(function() {
                    self.selectedArticle = false;
                    self.loadArticles();
                    if (request.getType() == 'POST') {
                        swal({
                            title: "Artikel opgslagen!",
                            text: 'Artikel is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Artikel aangepast!",
                            text: 'Artikel is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            createArticle: function () {
                this.selectedArticle = {'article_group_id': 0, 'title': '', 'summary': '', 'body': '', 'published': true, 'image_id': 0, 'image_name': ''};
                this.content = '';

                if (this.selectedArticleGroup)
                    this.selectedArticle.article_group_id = this.selectedArticleGroup.id;
            },

            createArticleGroup: function() {
                let value = $('[name=article_group]').val();

                if (value == "") {
                    this.newArticleGroupError = true;
                    alert("Nieuwsgroep naam kan niet leeg zijn.");
                    return;
                }

                this.newArticleGroupError = false;
                this.newArticleGroup = false;

                let self = this;
                $.ajax({
                    url: '/cms/articleGroups',
                    type: 'POST',
                    data: { title: value },
                    success: function(data) {
                        self.articleGroups.push(data);
                        self.selectedArticleGroup = data;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            onTextChange: function () {
                this.selectedArticle.body = this.content;
            },

            editArticle: function (article) {
                this.content = article.body;
                this.selectedArticle = article;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedArticle = false;
            },

            removeMedia: function() {
                this.selectedArticle.image_id = 0;
                this.selectedArticle.image_name = "";
            },

            removeArticle: function (article) {
                let self = this;
                swal({
                    title: "Artikel verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit artikel",
                }).then(function(){
                    self.doRemoveArticle(article);
                }).done();
            },

            removeArticleGroup: function (articleGroup) {
                let self = this;
                swal({
                    title: "Nieuwsgroep verwijderen?",
                    text: "Alle bijbehorende artikelen zullen ook verwijderd worden. Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze nieuwsgroep",
                }).then(function(){
                    self.doRemoveArticleGroup(articleGroup);
                }).done();
            },

            doRemoveArticle: function (article) {
                let self = this;
                $.ajax({
                    url: '/cms/articles/'+article.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = self.articles.indexOf(article);
                        self.articles.splice(index, 1);
                    }
                });
            },

            doRemoveArticleGroup: function (articleGroup) {
                let self = this;
                $.ajax({
                    url: '/cms/articleGroups/'+articleGroup.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = self.articleGroups.indexOf(articleGroup);
                        self.articleGroups.splice(index, 1);
                        self.selectedArticleGroup = _.head(self.articleGroups) ? _.head(self.articleGroups) : false;
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadArticleGroups();
                return this.loadArticles();
            },

            loadArticles: function() {
                let self = this;
                return $.get('/cms/articles', function (data) {
                    self.articles = data;
                });
            },

            loadArticleGroups: function() {
                let self = this;
                return $.get('/cms/articleGroups', function (data) {
                    if (data.length != 0) {
                        self.articleGroups = data;
                        self.selectedArticleGroup = _.head(data);
                    }
                });
            }

        },

        computed: {

        }
    }
</script>
