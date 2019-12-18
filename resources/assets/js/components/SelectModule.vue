<template>
    <div class="selectModule col-md-12">
        <div class="sidebar col-md-4">
            <ul class="list-group">
                <a href="#" class="list-group-item"
                   v-for="module in modules"
                   :class="{ active: (module.id == selectedModule.id) }"
                   v-on:click="selectedModule = module"
                >
                    {{ module.name }}
                </a>
            </ul>
        </div>
        <div class="col-md-8" v-if="selectedModule">
            <table class="table" v-if="selectedModule.table !== ''">
                <tr>
                    <th>#</th>
                    <th>Naam</th>
                    <th>Aanmaak datum</th>
                    <th></th>
                </tr>
                <tr v-for="(module, i) in selectedModules">
                    <td>{{ i+1 }}</td>
                    <td>{{ isset(module.name) ? module.name : module.title }}</td>
                    <td>{{ module.created_at | moment("dddd, D MMMM YYYY") | capitalize }}</td>
                    <td>
                        <a href="#" v-on:click="selectModule(module)">
                            <span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </td>
                </tr>
                <tr v-if="!selectedModules.length">
                    <td colspan="4">Er zijn geen modules gevonden.</td>
                </tr>
            </table>
            <button class="btn btn-primary" v-else v-on:click="selectModule(selectedModules[0])">
                <span class="glyphicon glyphicon-plus"></span> Module toevoegen
            </button>
        </div>
    </div>
</template>
<style>

</style>
<script>
    import AutoloadModal from './AutoloadModal.vue';

    export default {
        extends: AutoloadModal,

        data(){
            return {
                selectedModule: false,
                modules: [],
                selectedModules: []
            };
        },

        watch: {
            selectedModule: function (value) {
                this.loadModuleOptions(value);
            }
        },

        methods: {

            loadFromDatabase: function() {
                this.loadModules();
            },

            selectModule: function(module) {
                var name = this.isset(module.name) ? module.name : module.title;
                addModule(this.selectedModule.id, this.selectedModule.name, module.id, name);
                $('#selectModuleModal').modal('toggle');
            },

            loadModules: function() {
                var _this = this;
                $.get('/cms/modules', function (data) {
                     _this.modules = data;
                     _this.selectedModule = _.head(data);
                });
            },

            loadModuleOptions: function (module) {
                if (module.table !== '') {
                    var _this = this;
                    $.get('/cms/'+_.camelCase(module.table), function (data) {
                        _this.selectedModules = data;
                    });
                } else {
                    this.selectedModules = {
                        0: {
                            id: -1,
                            'name': module.name,
                        }
                    }
                }
            },

            isset: function () {
                var a = arguments,
                    l = a.length,
                    i = 0,
                    undef;

                if (l === 0) {
                    throw new Error('Empty isset');
                }

                while (i !== l) {
                    if (a[i] === undefined || a[i] === null) {
                        return false;
                    }
                    i++;
                }
                return true;
            }

        },

        computed: {

        },
    }
</script>
