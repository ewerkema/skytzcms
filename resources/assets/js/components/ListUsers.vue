<template>
    <div>
        <div v-if="!editMode()" class='list-users'>
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th></th>
                    </tr>
                    <tr v-for="(user, i) in users">
                        <td>{{ i+1 }}</td>
                        <td>{{ user.firstname }} {{ user.lastname }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td>
                            <a href="#" @click="editUser(user)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" @click="removeUser(user)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!users.length">
                        <td colspan="5">Er zijn geen gebruikers gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" @click="createUser()">Nieuwe gebruiker</button>
            </div>
        </div>
        <div class="editForm" v-if="editMode()">
            <form action="#" class="form-horizontal" id="userForm" @submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="firstname" class="col-md-3 control-label">Voornaam</label>

                    <div class="col-md-8">
                        <input type="text" id="firstname" name="firstname" v-model="selectedUser.firstname" class="form-control" placeholder="Voornaam" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-md-3 control-label">Achternaam</label>

                    <div class="col-md-8">
                        <input type="text" id="lastname" name="lastname" placeholder="Achternaam" v-model="selectedUser.lastname" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Email</label>

                    <div class="col-md-8">
                        <input type="email" id="email" name="email" placeholder="Email" v-model="selectedUser.email" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-md-3 control-label">Wachtwoord</label>

                    <div class="col-md-8">
                        <input type="password" id="password" name="password" placeholder="Wachtwoord" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="col-md-3 control-label">Wachtwoord bevestigen</label>

                    <div class="col-md-8">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Wachtwoord bevestigen" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="role" class="col-md-3 control-label">Rol</label>

                    <div class="col-md-8">
                        <select class="form-control" id="role" name="role" v-model="selectedUser.role">
                            <option value="" disabled>Selecteer een rol</option>
                            <option v-for="(name, role) in roles" :value="role">
                                {{ name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="userForm" class="btn btn-success right" @click="submitForm()">Gebruiker opslaan</button>
                        <button class="btn btn-default right" @click="cancelEdit">Annuleren</button>
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

    .list-users .btn.right {
        margin-left: 15px;
    }
</style>
<script>
    import AutoloadModal from './AutoloadModal.vue';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                userGroups: [],
                users: [],
                selectedUser: {},
                roles: {
                    'user': 'Gebruiker',
                    'admin': 'Admin',
                }
            };
        },

        methods: {
            submitForm: function () {
                let request = new Request('/cms/users');
                request.setForm('#userForm');
                request.setType('POST');

                request.addFields(['firstname', 'lastname', 'email', 'password', 'password_confirmation', 'role']);
                request.addCheckboxes(['published']);

                if (this.selectedUser.id != undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedUser.id);
                }

                let self = this;
                request.send(function() {
                    self.selectedUser = false;
                    self.loadUsers();
                    if (request.getType() == 'POST') {
                        swal({
                            title: "Gebruiker opgslagen!",
                            text: 'Gebruiker is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Gebruiker aangepast!",
                            text: 'Gebruiker is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            createUser: function () {
                this.selectedUser = {'firstname': '', 'lastname': '', 'email': '', 'password': '', 'password_confirmation': '', 'role': ''};
            },

            editMode: function() {
                return !_.isEmpty(this.selectedUser);
            },

            editUser: function (user) {
                this.selectedUser = user;
                this.selectedUser.role = (_.invert(this.roles))[user.role];
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedUser = {};
            },

            removeUser: function (user) {
                let self = this;
                swal({
                    title: "Gebruiker verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder deze gebruiker",
                }).then(function(){
                    self.doRemoveUser(user);
                }).done();
            },

            doRemoveUser: function (user) {
                let self = this;
                $.ajax({
                    url: '/cms/users/'+user.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = self.users.indexOf(user);
                        self.users.splice(index, 1);
                    }
                });
            },

            loadFromDatabase: function() {
                return this.loadUsers();
            },

            loadUsers: function() {
                let self = this;
                return $.get('/cms/users', function (data) {
                    self.users = data;
                });
            },

        }
    }
</script>
