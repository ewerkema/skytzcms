<template>
    <div>
        <div v-if="!changeSelectedForm && !editMode()" class='list-forms'>
            <div class="sidebar col-md-4">
                <ul class="list-group">
                    <a href="#" class="list-group-item"
                       v-for="form in forms"
                       :class="{ active: (form.id == selectedForm.id) }"
                       @click="selectedForm = form"
                    >
                        <span class="badge">{{ countFormFields(form.id) }}</span>
                        {{ form.name }}
                    </a>
                    <a href="#" class="list-group-item add-item"
                       @click="newForm = true"
                       v-if="!newForm"
                    >
                        Nieuw formulier
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                    <a href="#" class="list-group-item add-item"
                       v-if="newForm"
                       :class="{ 'has-error': newFormError }"
                    >
                        <input type="text" name="new_form" class="form-control" @keyup.enter="createForm()" placeholder="Formulier naam" />
                        <button class="btn btn-default" @click="newForm = false">Annuleren</button>
                        <button class="btn btn-success right" @click="createForm()">Opslaan</button>
                    </a>
                </ul>
            </div>
            <div class="col-md-8" v-if="selectedForm">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Veld</th>
                        <th>Naam</th>
                        <th>Verplicht</th>
                        <th></th>
                    </tr>
                    <tr v-for="(formField, i) in selectedFormFields">
                        <td>{{ i+1 }}</td>
                        <td>{{ formField.type | capitalize }}</td>
                        <td>{{ formField.name | capitalize }}</td>
                        <td>{{ (formField.required) ? "Ja" : "Nee" }}</td>
                        <td>
                            <a href="#" @click="editFormField(formField)">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="#" @click="removeFormField(formField.id)" class="right">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="!selectedFormFields.length">
                        <td colspan="4">Er zijn geen formulier velden gevonden.</td>
                    </tr>
                </table>

                <button class="btn btn-success right" @click="newFormField(selectedForm.id)"><span class="glyphicon glyphicon-plus"></span> Nieuw veld toevoegen</button>
                <div class="clear"></div>
                <button class="btn btn-default right" @click="changeSelectedForm = true"><span class="glyphicon glyphicon-cog"></span> Formulier instellingen</button>
                <button class="btn btn-danger right" @click="removeForm(selectedForm.id)">Verwijder formulier</button>
            </div>
            <div class="col-md-8" v-else>
                <p>Er is geen formulier geselecteerd.</p>
            </div>
        </div>
        <div class="editForm" v-if="changeSelectedForm">
            <form action="#" class="form-horizontal" id="formForm" @submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" id="name" name="name" :value="selectedForm.name" class="form-control" placeholder="Naam" required autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Email</label>

                    <div class="col-md-8">
                        <input type="text" id="email" name="email" :value="selectedForm.email" class="form-control" placeholder="Email" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="message" class="col-md-3 control-label">Success bericht</label>

                    <div class="col-md-8">
                        <textarea type="text" id="message" name="message" placeholder="Succes bericht" required autofocus>{{ selectedForm.message }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="recaptcha" class="col-md-3 control-label">ReCAPTCHA verificatie</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="recaptcha" id="recaptcha" :checked="selectedForm.recaptcha">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="formForm" class="btn btn-success right" @click="submitForm()">Formulier opslaan</button>
                        <button class="btn btn-default right" @click="cancelEdit($event)">Annuleren</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="editForm" v-if="editMode()">
            <form action="#" class="form-horizontal" id="formFieldForm" @submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <input type="hidden" name="form_id" :value="selectedFormField.form_id" />
                <div class="form-group">
                    <label for="type" class="col-md-3 control-label">Type</label>

                    <div class="col-md-8">
                        <select class="form-control" id="type" name="type" v-model="selectedFormField.type" @change="updateFormFieldType">
                            <option value="" disabled>Selecteer een type</option>
                            <option v-for="(name, type) in types" :value="type">
                                {{ name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" name="name" v-model="selectedFormField.name" class="form-control" placeholder="Naam" required autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="placeholder" class="col-md-3 control-label">Tijdelijke aanduiding</label>

                    <div class="col-md-8">
                        <input type="text" id="placeholder" name="placeholder" v-model="selectedFormField.placeholder" class="form-control" placeholder="Tijdelijke aanduiding" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="required" class="col-md-3 control-label">Verplicht veld</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="required" id="required" v-model="selectedFormField.required">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="hidden_name" class="col-md-3 control-label">Naam verbergen</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="hidden_name" id="hidden_name" v-model="selectedFormField.hidden_name">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>
                <div class="form-group" v-if="activateOptions">
                    <label for="required" class="col-md-3 control-label">Opties</label>

                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Naam</th>
                                <th>Acties</th>
                            </tr>
                            <tr v-for="(option, index) in selectedFormField.options"
                                class="option"
                                :key="option.id"
                            >
                                <td>{{ index+1 }}</td>
                                <td>
                                    <input type="text" :name="'option_'+index" v-model="option.name" class="option-name form-control" placeholder="Naam" />
                                </td>
                                <td>
                                    <a href="#" @click="removeOption(option)" class="center" title="Verwijderen">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!selectedFormField.options.length">
                                <td colspan="4">Er zijn geen opties gevonden.</td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-primary right" @click="addOption()">Nieuwe optie toevoegen <span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="formFieldForm" class="btn btn-success right" @click="submitFormFieldForm()">Veld opslaan</button>
                        <button class="btn btn-default right" @click="cancelEdit($event)">Annuleren</button>
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

    .list-forms .btn.right {
        margin-left: 15px;
        margin-bottom: 15px;
    }
</style>
<script>
    import AutoloadModal from './AutoloadModal.vue';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                forms: [],
                formFields: [],
                selectedForm: false,
                selectedFormField: {},
                selectedFormFields: [],
                changeSelectedForm: false,
                newForm: false,
                newFormError: false,
                types: {
                    'text': 'Simpel veld',
                    'textarea' : 'Groot tekst veld',
                    'email' : 'E-mail veld',
                    'number' : 'Getal veld',
                    'checkbox' : 'Checkbox veld',
                    'radio' : 'Keuzerondje (radio button)',
                    'select' : 'Dropdown veld',
                },
                selectedType: false,
                activateOptions: false,
                optionCounter: 0,
            };
        },

        watch: {
            formFields: function (val) {
                if (this.selectedForm === undefined) return false;
                this.selectedFormFields = this.getSelectedFormFields(this.selectedForm.id);
            },

            selectedForm: function (val) {
                if (this.selectedForm === undefined) return false;
                this.selectedFormFields = this.getSelectedFormFields(this.selectedForm.id);
            },

        },

        methods: {
            countFormFields: function (formId) {
                return this.getSelectedFormFields(formId).length;
            },

            getSelectedFormFields: function (formId) {
                return _.filter(this.formFields, ['form_id', formId]);
            },

            isOptionType: function (type) {
                return type === 'radio' || type === 'select';
            },

            editMode: function() {
                return !_.isEmpty(this.selectedFormField);
            },

            updateFormFieldType: function() {
                let type = this.selectedFormField.type;
                if (this.isOptionType(type)) {
                    if (this.selectedFormField.options === undefined) {
                        this.selectedFormField.options = [];
                    }

                    if (this.selectedFormField.options.length === 0)
                        this.addOption();

                    this.activateOptions = true;
                } else {
                    this.activateOptions = false;
                    this.resetFormFieldOptions();
                }
            },

            resetFormFieldOptions: function() {
                if (this.selectedFormField.options !== undefined) {
                    this.selectedFormField.options = [];
                }
            },

            submitForm: function () {
                let request = new Request('/cms/forms/'+this.selectedForm.id);
                request.setForm('#formForm');
                request.setType('PATCH');
                request.addFields(['name', 'message', 'email']);
                request.addCheckboxes(['recaptcha']);

                let self = this;
                request.send(function(data) {
                    self.changeSelectedForm = false;
                    self.loadForms();
                    swal({
                        title: "Formulier aangepast!",
                        text: 'Formulier is succesvol aangepast.',
                        type: "success",
                        timer: 2000
                    }).done();
                });
            },

            submitFormFieldForm: function () {
                let request = new Request('/cms/formFields');
                request.setForm('#formFieldForm');
                request.setType('POST');
                request.addFields(['name', 'type', 'placeholder', 'form_id']);
                request.addCheckboxes(['required', 'hidden_name']);

                if (!this.isOptionType(this.selectedFormField.type))
                    this.resetFormFieldOptions();

                request.addValue("options", this.selectedFormField.options);

                if (this.selectedFormField.id !== undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedFormField.id);
                }

                let self = this;
                request.send(function(data) {
                    self.selectedFormField = {};
                    self.loadFormsFields();

                    if (request.getType() === 'POST') {
                        swal({
                            title: "Veld opgeslagen!",
                            text: 'Veld is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Veld aangepast!",
                            text: 'Veld is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            createForm: function() {
                let value = $('[name=new_form]').val();

                if (value == "") {
                    this.newFormError = true;
                    alert("Formulier naam kan niet leeg zijn.");
                    return;
                }

                this.newFormError = false;
                this.newForm = false;

                let self = this;
                $.ajax({
                    url: '/cms/forms',
                    type: 'POST',
                    data: { name: value },
                    success: function(data) {
                        self.forms.push(data);
                        self.selectedForm = data;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            newFormField: function(formId) {
                this.selectedFormField = {
                    'type': '',
                    'name': '',
                    'form_id': formId,
                    'placeholder': '',
                    'required': true,
                    'hidden_name': false,
                    'options': [],
                };
            },

            addOption: function () {
                if (this.selectedFormField.options == null)
                    this.resetFormFieldOptions();

                this.selectedFormField.options.push({
                    id: this.optionCounter++,
                    name: "",
                    value: ""
                });
            },

            removeOption: function (option) {
                let index = this.selectedFormField.options.indexOf(option);
                this.selectedFormField.options.splice(index, 1);
            },

            editFormField: function (formField) {
                this.selectedFormField = formField;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.changeSelectedForm = false;
                this.selectedFormField = {};
            },

            removeFormField: function (formFieldId) {
                let self = this;
                swal({
                    title: "Veld verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit veld",
                }).then(function(){
                    self.doRemoveFormField(formFieldId);
                }).done();
            },

            removeForm: function (formId) {
                let self = this;
                swal({
                    title: "Formulier verwijderen?",
                    text: "Alle bijbehorende velden zullen ook verwijderd worden. Deze wijzigingen kunnen niet meer ongedaan worden.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit formulier",
                }).then(function(){
                    self.doRemoveForm(formId);
                }).done();
            },

            doRemoveFormField: function (formFieldId) {
                let self = this;
                $.ajax({
                    url: '/cms/formFields/'+formFieldId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = _.findIndex(self.formFields, o => o.id === formFieldId);
                        self.formFields.splice(index, 1);
                    }
                });
            },

            doRemoveForm: function (formId) {
                let self = this;
                $.ajax({
                    url: '/cms/forms/'+formId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = _.findIndex(self.forms, o => o.id === formId);
                        self.forms.splice(index, 1);
                        self.selectedForm = _.head(self.forms);
                    }
                });
            },

            loadFromDatabase: function() {
                this.loadFormsFields();
                return this.loadForms();
            },

            loadFormsFields: function() {
                let self = this;
                return $.get('/cms/formFields', function (data) {
                    self.formFields = data;
                });
            },

            loadForms: function() {
                let self = this;
                return $.get('/cms/forms', function (data) {
                    if (data.length != 0) {
                        self.forms = data;
                        self.selectedForm = _.head(data);
                    }
                });
            }

        }
    }
</script>
