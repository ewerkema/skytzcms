<template>
    <div v-if="!selectedArticle" class='list-articles'>
        <div class="sidebar col-md-4">
            <ul class="list-group">
                <a href="#" class="list-group-item"
                   v-for="articleGroup in articleGroups"
                   :class="{ active: (articleGroup.id == selectedArticleGroup) }"
                   v-on:click="selectedArticleGroup = articleGroup.id"
                >
                    <span class="badge">{{ countArticles(articleGroup.id) }}</span>
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
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Titel</th>
                    <th>Publiceer datum</th>
                    <th></th>
                </tr>
                <tr v-for="(i, article) in selectedArticles">
                    <td>{{ i+1 }}</td>
                    <td>{{ article.title | truncate 30 }}</td>
                    <td>{{ article.created_at | moment "dddd, D MMMM YYYY" | capitalize }}</td>
                    <td>
                        <a href="#" v-on:click="editArticle(article)">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a href="#" v-on:click="removeArticle(article.id)" class="right">
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
    </div>
    <div class="editForm" v-if="selectedArticle">
        <form action="#" class="form-horizontal" id="articleForm" v-on:submit.prevent>
            <div class="alert form-message" role="alert" style="display: none;"></div>
            <div class="form-group">
                <label for="article_group_id" class="col-md-3 control-label">Nieuwsgroep</label>

                <div class="col-md-8">
                    <select class="form-control" id="article_group_id" name="article_group_id">
                        <option value="" :selected="!selectedArticle.article_group_id" disabled>Selecteer een nieuwsgroep</option>
                        <option v-for="articleGroup in articleGroups" value="{{ articleGroup.id }}" :selected="selectedArticle.article_group_id == articleGroup.id">
                            {{ articleGroup.title }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-md-3 control-label">Titel</label>

                <div class="col-md-8">
                    <input type="text" id="title" name="title" value="{{ selectedArticle.title }}" class="form-control" placeholder="Titel" required autofocus />
                </div>
            </div>
            <div class="form-group">
                <label for="summary" class="col-md-3 control-label">Introductie</label>

                <div class="col-md-8">
                    <textarea type="text" id="summary" name="summary" placeholder="Introductie" required autofocus>{{ selectedArticle.summary }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="body" class="col-md-3 control-label">Artikel</label>

                <div class="col-md-8">
                    <editor name="body" id="body" language="nl-NL" :model.sync="selectedArticle.body"></editor>
                </div>
            </div>
            <div class="form-group">
                <label for="published" class="col-md-3 control-label">Gepubliceerd</label>

                <div class="col-md-8">
                    <label class="Switch">
                        <input type="checkbox" name="published" id="published" :checked="selectedArticle.published">
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
    export default {
        data(){
            return {
                articleGroups: [],
                articles: [],
                selectedArticleGroup: false,
                selectedArticles: [],
                selectedArticle: false,
                newArticleGroup: false,
                newArticleGroupError: false
            };
        },

        components: {
            "editor": require('./vue-html-editor')
        },

        created() {
            this.loadFromDatabase();
        },

        watch: {

            selectedArticleGroup: function (val) {
                this.selectedArticles = this.getSelectedArticles(val);
            },

            articles: function (val) {
                this.selectedArticles = this.getSelectedArticles(this.selectedArticleGroup);
            }

        },

        methods: {

            countArticles: function (articleGroupId) {
                return this.getSelectedArticles(articleGroupId).length;
            },

            getSelectedArticles: function (articleGroupId) {
                return _.filter(this.articles, ['article_group_id', articleGroupId]);
            },

            submitForm: function () {
                var request = new Request('/cms/articles');
                request.setForm('#articleForm');
                request.setType('POST');

                request.addFields(['article_group_id', 'title', 'summary', 'body', 'published']);
                request.addCheckboxes(['published']);

                if (this.selectedArticle.id != undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedArticle.id);
                }

                var _this = this;
                request.send(function(data) {
                    _this.selectedArticle = false;
                    _this.loadArticles();
                    _this.selectedArticleGroup = data.article_group_id;
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
                this.selectedArticle = ['article_group_id', 'title', 'summary', 'body', 'published'];
                this.selectedArticle.published = true;

                if (this.selectedArticleGroup)
                    this.selectedArticle.article_group_id = this.selectedArticleGroup;
            },

            createArticleGroup: function() {
                var value = $('[name=article_group]').val();

                if (value == "") {
                    this.newArticleGroupError = true;
                    alert("Nieuwsgroep naam kan niet leeg zijn.");
                    return;
                }

                this.newArticleGroupError = false;
                this.newArticleGroup = false;

                var _this = this;
                $.ajax({
                    url: '/cms/articleGroups',
                    type: 'POST',
                    data: { title: value },
                    success: function(data) {
                        _this.articleGroups.push(data);
                        _this.selectedArticleGroup = data.id;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            editArticle: function (article) {
                this.selectedArticle = article;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault()
                this.selectedArticle = false;
            },

            removeArticle: function (articleId) {
                var _this = this;
                swal({
                    title: "Artikel verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit artikel",
                }).then(function(){
                    _this.doRemoveArticle(articleId);
                }).done();
            },

            removeArticleGroup: function (articleGroupId) {
                var _this = this;
                swal({
                    title: "Nieuwsgroep verwijderen?",
                    text: "Alle bijbehorende artikelen zullen ook verwijderd worden. Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze nieuwsgroep",
                }).then(function(){
                    _this.doRemoveArticleGroup(articleGroupId);
                    _this.selectedArticleGroup = _.head(_this.articleGroups).id;
                }).done();
            },

            doRemoveArticle: function (articleId) {
                var _this = this;
                $.ajax({
                    url: '/cms/articles/'+articleId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.articles.$remove(_.find(_this.articles, ['id', articleId]));
                    }
                });
            },

            doRemoveArticleGroup: function (articleGroupId) {
                var _this = this;
                $.ajax({
                    url: '/cms/articleGroups/'+articleGroupId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.articleGroups.$remove(_.find(_this.articleGroups, ['id', articleGroupId]));
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadArticles();
                this.loadArticleGroups();
            },

            loadArticles: function() {
                var _this = this;
                $.get('/cms/articles', function (data) {
                    _this.articles = data;
                });
            },

            loadArticleGroups: function() {
                var _this = this;
                $.get('/cms/articleGroups', function (data) {
                    if (data.length != 0) {
                        _this.articleGroups = data;
                        _this.selectedArticleGroup = _.head(data).id;
                    }
                });
            }

        },

        filters: {

            truncate: function(string, value) {
                return (string.length > value) ? string.substring(0, value) + '...' : string;
            }

        },

        computed: {

        }
    }
</script>
