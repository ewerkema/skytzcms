<template>
    <div>
        <div v-if="!selectedProject" class='list-projects'>
            <div class="sidebar col-md-4">
                <ul class="list-group">
                    <a href="#" class="list-group-item"
                       v-for="projectGroup in projectGroups"
                       :class="{ active: (projectGroup.id == selectedProjectGroup) }"
                       v-on:click="selectedProjectGroup = projectGroup.id"
                    >
                        <span class="badge">{{ countProjects(projectGroup.id) }}</span>
                        {{ projectGroup.title }}
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-on:click="newProjectGroup = true"
                       v-if="!newProjectGroup"
                    >
                        Voeg project groep toe
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-if="newProjectGroup"
                       :class="{ 'has-error': newProjectGroupError }"
                    >
                        <input type="text" name="project_group" class="form-control" @keyup.enter="createProjectGroup()" placeholder="Project groep naam" />
                        <button class="btn btn-default" v-on:click="newProjectGroup = false">Annuleren</button>
                        <button class="btn btn-success right" v-on:click="createProjectGroup()">Opslaan</button>
                    </a>
                </ul>
            </div>
            <div class="col-md-8" v-if="selectedProjectGroup">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Titel</th>
                        <th>Publiceer datum</th>
                        <th></th>
                    </tr>
                    <tr v-for="(i, project) in selectedProjects">
                        <td>{{ i+1 }}</td>
                        <td>{{ project.title | truncate 30 }}</td>
                        <td>{{ project.created_at | moment "dddd, D MMMM YYYY" | capitalize }}</td>
                        <td>
                            <a href="#" v-on:click="editProject(project)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" v-on:click="removeProject(project.id)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!selectedProjects.length">
                        <td colspan="4">Er zijn geen nieuwsberichten gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" v-on:click="createProject()">Nieuw project</button>
                <button class="btn btn-danger right" v-on:click="removeProjectGroup(selectedProjectGroup)">Verwijder project groep</button>
            </div>
            <div class="col-md-8" v-else>
                <p>Er is geen project groep geselecteerd.</p>
            </div>
        </div>
        <div class="editForm" v-if="selectedProject">
            <form action="#" class="form-horizontal" id="projectForm" v-on:submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="project_group_id" class="col-md-3 control-label">Project groep</label>

                    <div class="col-md-8">
                        <select class="form-control" id="project_group_id" name="project_group_id">
                            <option value="" :selected="!selectedProject.project_group_id" disabled>Selecteer een project groep</option>
                            <option v-for="projectGroup in projectGroups" :value="projectGroup.id" :selected="selectedProject.project_group_id == projectGroup.id">
                                {{ projectGroup.title }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Titel</label>

                    <div class="col-md-8">
                        <input type="text" id="title" name="title" :value="selectedProject.title" class="form-control" placeholder="Titel" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-md-3 control-label">Adres (optioneel)</label>

                    <div class="col-md-8">
                        <input type="text" id="address" name="address" :value="selectedProject.address" class="form-control" placeholder="Project adres" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="summary" class="col-md-3 control-label">Introductie</label>

                    <div class="col-md-8">
                        <textarea type="text" id="summary" name="summary" placeholder="Introductie">{{ selectedProject.summary }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image_1" class="col-md-3 control-label">Project afbeeldingen</label>

                    <div class="col-md-8">
                        <div class="input-group input-pointer input-image" v-for="(i, projectImage) in selectedProject.images">
                            <input type="hidden" name="image_ids[]" :id="'image_'+i" :value="projectImage.image.id || 0" class="form-control" />
                            <span class="input-group-addon" :id="'media-picture-'+i" v-on:click="addMedia(i)"><span class="glyphicon glyphicon-picture"></span></span>
                            <input type="text" name="image_names[]" :id="'image_name_'+i" v-on:click="addMedia(i)" :value="projectImage.image.name" class="form-control no-border-radius" placeholder="Project afbeelding" />
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" v-on:click="removeImage(i)"><span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                        </div>

                        <div class="btn btn-success" v-on:click="addImage($event)"><span class="glyphicon glyphicon-add"></span> Afbeelding toevoegen</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="body" class="col-md-3 control-label">Project beschrijving</label>

                    <div class="col-md-8">
                        <editor name="body" id="body" language="nl-NL" :model.sync="selectedProject.body"></editor>
                    </div>
                </div>
                <div class="form-group">
                    <label for="published" class="col-md-3 control-label">Gepubliceerd</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="published" id="published" :checked="selectedProject.published">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="projectForm" class="btn btn-success right" v-on:click="submitForm()">Project opslaan</button>
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

    .list-projects .btn.right {
        margin-left: 15px;
    }
</style>
<script>
    import ListBase from './ListBase.vue';

    export default {
        extends: ListBase,

        data(){
            return {
                projectGroups: [],
                projects: [],
                selectedProjectGroup: false,
                selectedProjects: [],
                selectedProject: false,
                newProjectGroup: false,
                newProjectGroupError: false,
            };
        },

        components: {
            "editor": require('./vue-html-editor')
        },

        watch: {

            selectedProjectGroup: function (val) {
                this.selectedProjects = this.getSelectedProjects(val);
            },

            projects: function (val) {
                this.selectedProjects = this.getSelectedProjects(this.selectedProjectGroup);
            },

        },

        methods: {

            countProjects: function (projectGroupId) {
                return this.getSelectedProjects(projectGroupId).length;
            },

            getSelectedProjects: function (projectGroupId) {
                return _.filter(this.projects, ['project_group_id', projectGroupId]);
            },

            submitForm: function () {
                let request = new Request('/cms/projects');
                request.setForm('#projectForm');
                request.setType('POST');

                request.addFields(['project_group_id', 'title', 'summary', 'body', 'address', 'published']);
                request.addArrays(['image_ids']);
                request.addCheckboxes(['published']);

                if (this.selectedProject.id != undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedProject.id);
                }

                let self = this;
                request.send(function(data) {
                    self.selectedProject = false;
                    self.loadProjects();
                    self.selectedProjectGroup = data.project_group_id;
                    if (request.getType() == 'POST') {
                        swal({
                            title: "Project opgeslagen!",
                            text: 'Project is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Project aangepast!",
                            text: 'Project is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            createProject: function () {
                this.selectedProject = {
                    'project_group_id': (this.selectedProjectGroup) ? this.selectedProjectGroup : 0,
                    'title': '',
                    'summary': '',
                    'body': '',
                    'published': true,
                    'images': [],
                };
                this.addImage();
            },

            createProjectGroup: function() {
                let value = $('[name=project_group]').val();

                if (value == "") {
                    this.newProjectGroupError = true;
                    alert("Project groep naam kan niet leeg zijn.");
                    return;
                }

                this.newProjectGroupError = false;
                this.newProjectGroup = false;

                let self = this;
                $.ajax({
                    url: '/cms/projectGroups',
                    type: 'POST',
                    data: { title: value },
                    success: function(data) {
                        self.projectGroups.push(data);
                        self.selectedProjectGroup = data.id;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            editProject: function (project) {
                this.selectedProject = project;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedProject = false;
            },

            addMedia: function(inputId) {
                $('.input-image').each(function() {
                    $(this).find('.selected_media_id').removeClass('selected_media_id');
                    $(this).find('.selected_media_name').removeClass('selected_media_name');
                });

                let nameEl = $('#image_name_' + inputId);
                let imageEl = $('#image_' + inputId);

                imageEl.addClass('selected_media_id');
                nameEl.addClass('selected_media_name');

                selectMedia();
            },

            removeImage: function(imageId) {
                this.selectedProject.images.splice(imageId, 1);
            },

            addImage: function(event) {
                if (event) event.preventDefault();
                this.selectedProject.images.push({
                    'image': {
                        'id': 0,
                        'name': '',
                    }
                });
            },

            removeProject: function (projectId) {
                let self = this;
                swal({
                    title: "Project verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit project",
                }).then(function(){
                    self.doRemoveProject(projectId);
                }).done();
            },

            removeProjectGroup: function (projectGroupId) {
                let self = this;
                swal({
                    title: "Project groep verwijderen?",
                    text: "Alle bijbehorende projecten zullen ook verwijderd worden. Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze project groep",
                }).then(function(){
                    self.doRemoveProjectGroup(projectGroupId);
                }).done();
            },

            doRemoveProject: function (projectId) {
                let self = this;
                $.ajax({
                    url: '/cms/projects/'+projectId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        self.projects.$remove(_.find(self.projects, ['id', projectId]));
                    }
                });
            },

            doRemoveProjectGroup: function (projectGroupId) {
                let self = this;
                $.ajax({
                    url: '/cms/projectGroups/'+projectGroupId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        self.projectGroups.$remove(_.find(self.projectGroups, ['id', projectGroupId]));
                        self.selectedProjectGroup = _.head(self.projectGroups) ? _.head(self.projectGroups).id : false;
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadProjects();
                this.loadProjectGroups();
            },

            loadProjects: function() {
                let self = this;
                $.get('/cms/projects', function (data) {
                    self.projects = data;
                });
            },

            loadProjectGroups: function() {
                let self = this;
                $.get('/cms/projectGroups', function (data) {
                    if (data.length != 0) {
                        self.projectGroups = data;
                        self.selectedProjectGroup = _.head(data).id;
                    }
                });
            }

        },

        computed: {

        }
    }
</script>
