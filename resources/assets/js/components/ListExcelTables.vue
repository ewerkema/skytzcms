<template>
    <div class='list-html-blocks'>
        <div class="sidebar col-md-4">
            <ul class="list-group">
                <a href="#" class="list-group-item"
                   v-for="excelTable in excelTables"
                   :class="{ active: (excelTable.id == selectedExcelTable) }"
                   v-on:click="selectedExcelTable = excelTable.id"
                >
                    {{ excelTable.name }}
                </a>
                <a href="#" class="list-group-item add-item"
                   v-on:click="newExcelTable = true"
                   v-if="!newExcelTable"
                >
                    Voeg excel tabel toe
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
                <a href="#" class="list-group-item add-item"
                   v-if="newExcelTable"
                   :class="{ 'has-error': newExcelTableError }"
                >
                    <input type="text" name="html_block_name" class="form-control" @keyup.enter="createExcelTable()" placeholder="Excel tabel naam" />
                    <button class="btn btn-default" v-on:click="newExcelTable = false">Annuleren</button>
                    <button class="btn btn-success right" v-on:click="createExcelTable()">Opslaan</button>
                </a>
            </ul>
        </div>
        <div class="col-md-8" v-if="selectedExcelTable && getExcelTable(selectedExcelTable)">
            <form action="#" method="POST" class="form-horizontal editForm" id="excelTableForm" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Naam</label>

                    <div class="col-md-8">
                        <input type="text" id="name" name="name" :value="getExcelTable(selectedExcelTable).name" class="form-control" placeholder="Naam" required autofocus />
                    </div>
                </div>
              <div class="form-group">
                <label for="name" class="col-md-3 control-label">Excel bestand</label>

                <div class="col-md-8">
                  <input type="file" id="file" name="file" class="form-control" placeholder="Excel bestand" required autofocus />
                </div>
              </div>
                <div class="form-group">
                    <label for="html" class="col-md-3 control-label">Tabel</label>

                    <div class="col-md-8">
                        <textarea id="html" name="html" placeholder="Excel tabel" disabled>{{ getExcelTable(selectedExcelTable).table }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button class="btn btn-success right" type="submit">Excel tabel opslaan</button>
                        <button class="btn btn-danger right" v-on:click="removeExcelTable(selectedExcelTable)">Verwijder excel tabel</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8" v-else>
            <p>Er is geen excel tabel geselecteerd.</p>
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
                excelTables: [],
                selectedExcelTable: false,
                newExcelTable: false,
                newExcelTableError: false
            };
        },

        methods: {
            getExcelTable: function(excelTableId) {
                return _.find(this.excelTables, ['id', excelTableId]);
            },

            submitForm: function() {
              let request = new Request('/cms/excelTables');
              request.setForm('#excelTableForm');

              let form = $('#excelTableForm');
              let formData = new FormData(form[0]);
              formData.append('_method', 'PATCH');
              $.ajax({
                url: '/cms/excelTables/' + this.selectedExcelTable,
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: (data) => {
                  this.excelTables[_.findIndex(this.excelTables, (excelTable) => excelTable.id === data.id)] = data;
                  swal({
                    title: "Excel tabel aangepast!",
                    text: 'Excel tabel is succesvol aangepast.',
                    type: "success",
                    timer: 2000
                  }).done();
                  request.clearError();
                  this.$forceUpdate();
                },
                error: (errorData) => {
                  request.showError(errorData);
                }
              });
            },

            createExcelTable: function() {
                let value = $('[name=html_block_name]').val();

                if (value == "") {
                    this.newExcelTableError = true;
                    alert("Excel tabel naam kan niet leeg zijn.");
                    return;
                }

                this.newExcelTableError = false;
                this.newExcelTable = false;

                let self = this;
                $.ajax({
                    url: '/cms/excelTables',
                    type: 'POST',
                    data: { name: value },
                    success: function(data) {
                        self.excelTables.push(data);
                        self.selectedExcelTable = data.id;
                    },
                    error: function() {
                        alert("Er ging iets fout, probeer het later opnieuw");
                    }
                });
            },

            removeExcelTable: function (excelTableId) {
                let self = this;
                swal({
                    title: "Html block verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit blok",
                }).then(function(){
                    self.doRemoveExcelTable(excelTableId);
                }).done();
            },

            doRemoveExcelTable: function (excelTableId) {
                let self = this;
                $.ajax({
                    url: '/cms/excelTables/'+excelTableId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        let index = _.findIndex(self.excelTables, excelTable => excelTable.id === excelTableId);
                        self.excelTables.splice(index, 1);
                        self.selectedExcelTable = (_.head(self.excelTables) !== undefined) ? _.head(self.excelTables).id : false;
                    }
                });
            },

            loadFromDatabase: function() {
                return this.loadExcelTables();
            },

            loadExcelTables: function() {
                let self = this;
                return $.get('/cms/excelTables', function (data) {
                    if (data.length != 0) {
                        self.excelTables = data;
                        self.selectedExcelTable = _.head(data).id;
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
