<template>
    <div class='list-social'>
        <div class="sidebar col-md-4">
            <ul class="list-group">
                <a href="#" class="list-group-item"
                   v-for="social in socials"
                   :class="{ active: (social.id == selectedSocial) }"
                   v-on:click="selectedSocial = social.id"
                >
                    {{ social.name }}
                </a>
                <a href="#" class="list-group-item add-item"
                   v-on:click="newSocial = true"
                   v-if="!newSocial"
                >
                    Voeg social media item toe
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
                <a href="#" class="list-group-item add-item"
                   v-if="newSocial"
                   :class="{ 'has-error': newSocialError }"
                >
                    <input type="text" name="social_name" class="form-control" @keyup.enter="createSocial()" placeholder="Social media item naam" />
                    <button class="btn btn-default" v-on:click="newSocial = false">Annuleren</button>
                    <button class="btn btn-success right" v-on:click="createSocial()">Opslaan</button>
                </a>
            </ul>
        </div>
        <div class="col-md-8" v-if="selectedSocial && getSocial(selectedSocial)">
            <form action="#" class="form-horizontal editForm" id="socialForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="type" class="col-md-3 control-label">Type</label>

                    <div class="col-md-8">
                        <select class="form-control" id="type" name="type">
                            <option value="" :selected="!getSocial(selectedSocial).type" disabled>Selecteer een social media type</option>
                            <option v-for="(type,name) in socialMediaTypes" :value="type" :selected="getSocial(selectedSocial).type === type">
                                {{ name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" id="name" name="name" :value="getSocial(selectedSocial).name" class="form-control" placeholder="Naam" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="url" class="col-md-3 control-label">URL</label>

                    <div class="col-md-8">
                        <input type="text" id="url" name="url" :value="getSocial(selectedSocial).url" class="form-control" placeholder="URL" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="socialForm" class="btn btn-success right" v-on:click="submitForm()">Social media item opslaan</button>
                        <button class="btn btn-danger right" v-on:click="removeSocial(selectedSocial)">Verwijder social media item</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8" v-else>
            <p>Er is geen social media item geselecteerd.</p>
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

        props: ['socialMediaTypes'],

        data(){
            return {
                socials: [],
                selectedSocial: false,
                newSocial: false,
                newSocialError: false,
            };
        },

        methods: {

            getSocial: function(socialId) {
                return _.find(this.socials, ['id', socialId]);
            },

            submitForm: function () {
                let request = new Request('/cms/socials');
                request.setForm('#socialForm');
                request.setType('PATCH');
                request.addToUrl(this.selectedSocial);

                request.addFields(['type', 'name', 'url']);

                let _this = this;
                request.send(function(data) {
                    _this.socials[_.findIndex(_this.socials, function(social) { return social.id == data.id })] = data;
                    swal({
                        title: "Social media item aangepast!",
                        text: 'Social media item is succesvol aangepast.',
                        type: "success",
                        timer: 2000
                    }).done();
                });
            },

            createSocial: function() {
                let value = $('[name=social_name]').val();

                if (value == "") {
                    this.newSocialError = true;
                    alert("Social media item naam kan niet leeg zijn.");
                    return;
                }

                this.newSocialError = false;
                this.newSocial = false;

                let _this = this;
                $.ajax({
                    url: '/cms/socials',
                    type: 'POST',
                    data: { name: value },
                    success: function(data) {
                        _this.socials.push(data);
                        _this.selectedSocial = data.id;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            removeSocial: function (socialId) {
                let _this = this;
                swal({
                    title: "Social media item verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit social media item",
                }).then(function(){
                    _this.doRemoveSocial(socialId);
                }).done();
            },

            doRemoveSocial: function (socialId) {
                let _this = this;
                $.ajax({
                    url: '/cms/socials/'+socialId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.socials.$remove(_.find(_this.socials, ['id', socialId]));
                        _this.selectedSocial = (_.head(_this.socials) !== undefined) ? _.head(_this.socials).id : false;
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadSocials();
            },

            loadSocials: function() {
                let _this = this;
                $.get('/cms/socials', function (data) {
                    if (data.length != 0) {
                        _this.socials = data;
                        _this.selectedSocial = _.head(data).id;
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
