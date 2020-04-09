<template>
    <div class="menu-manager" :key="componentKey">
        <div v-if="!selectedMenuItem">
            <div class="row">
                <button class="btn btn-success pull-right" @click="newMenuItem()">Menu item toevoegen</button>
            </div>
            <ul class="sortable sortable-menu" v-if="totalMenuItems > 0">
                <li v-for="menuItem in menu" class="menu-item" :id="'page_' + menuItem.id" :data-id="menuItem.id">
                    <div class="flex flex-between">
                        <div><span class="glyphicon glyphicon-move"></span> <span v-html="displayMenuItem(menuItem)"></span></div>
                        <div>
                            <button class="btn btn-sm btn-success" @click="editMenuItem(menuItem)"><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-sm btn-danger" @click="removeMenuItem(menuItem)"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                    </div>

                    <ul v-if="menuItem.sub_items.length > 0">
                        <li v-for="subMenuItem in menuItem.sub_items" class="menu-item" :id="'page_' + subMenuItem.id" :data-id="subMenuItem.id">
                            <div class="flex flex-between">
                                <div><span class="glyphicon glyphicon-move"></span> <span v-html="displayMenuItem(subMenuItem)"></span></div>
                                <div>
                                    <button class="btn btn-sm btn-success" @click="editMenuItem(subMenuItem)"><span class="glyphicon glyphicon-pencil"></span></button>
                                    <button class="btn btn-sm btn-danger" @click="removeMenuItem(subMenuItem)"><span class="glyphicon glyphicon-remove"></span></button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
            <p v-else>Er staan geen pagina's in het menu.</p>
        </div>
        <div class="editForm" v-else>
            <form action="#" class="form-horizontal" id="menuForm" @submit.prevent>
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <p><i>Selecteer een pagina of vul een losse link in:</i></p>
                <div class="form-group">
                    <label for="page_id" class="col-md-3 control-label">Pagina</label>

                    <div class="col-md-8">
                        <select class="form-control" id="page_id" name="page_id" v-model="selectedMenuItem.page_id">
                            <option :value="null">Geen pagina</option>
                            <option v-for="page in pages" :value="page.id">
                                {{ page.title }} {{ page.menu_items_count ? '(Reeds aanwezig in menu)' : '' }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="link" class="col-md-3 control-label">Losse link</label>

                    <div class="col-md-8">
                        <input type="text" id="link" name="link" v-model="selectedMenuItem.link" class="form-control" placeholder="URL" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="title" class="col-md-3 control-label">Link naam</label>

                    <div class="col-md-8">
                        <input type="text" id="title" name="title" v-model="selectedMenuItem.title" class="form-control" placeholder="Andere link naam opgeven..." />
                    </div>
                </div>

                <div class="form-group">
                    <label for="open_in_new_tab" class="col-md-3 control-label">Openen in nieuw tabblad</label>

                    <div class="col-md-8">
                        <label class="Switch">
                            <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" v-model="selectedMenuItem.open_in_new_tab">
                            <div class="Switch__slider"></div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button form="menuForm" class="btn btn-success right" @click="submitForm()">{{ editPage ? 'Menu item opslaan' : 'Toevoegen aan menu' }}</button>
                        <button class="btn btn-default right" @click="cancelEdit($event)">Annuleren</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import AutoloadModal from "./AutoloadModal";

    export default {
        extends: AutoloadModal,

        data() {
            return {
                menu: {},
                pages: [],
                selectedMenuItem: false,
                componentKey: 0,
            }
        },

        computed: {
            totalMenuItems: function() {
                return _.size(this.menu);
            },

            editPage: function() {
                return this.selectedMenuItem.id !== undefined;
            }
        },

        methods: {
            loadFromDatabase: function() {
                this.loadMenu();
                return this.loadPages();
            },

            loadMenu: function() {
                let _this = this;
                return $.get('/cms/menu', function (data) {
                    _this.menu = data.menu;
                    _this.initDragging();
                });
            },

            loadPages: function() {
                let _this = this;
                return $.get('/cms/pages', function (data) {
                    _this.pages = data;
                });
            },

            initDragging: function() {
                let _this = this;
                this.forceUpdate();
                this.$nextTick(() => {
                    $('.sortable-menu').nestedSortable({
                        listType: 'ul',
                        handle: 'div',
                        items: 'li',
                        toleranceElement: '> div',
                        maxLevels: 2,
                        placeholder: 'placeholder',
                        forcePlaceholderSize: true,
                        relocate: function() {
                            _this.saveOrder();
                        }
                    });
                });
            },

            newMenuItem: function() {
                this.selectedMenuItem = {
                    page_id: null,
                    parent_id: false,
                    link: '',
                    open_in_new_tab: false,
                };
            },

            displayMenuItem: function (menuItem) {
                let type = !this.isLink(menuItem) ? 'Pagina' : `Losse link naar ${menuItem.link}`;
                let external = menuItem.open_in_new_tab ? `<span class="glyphicon glyphicon-new-window"></span>` : '';
                return `<b>${menuItem.linkName}:</b> ${type} ${external}`;
            },

            cancelEdit: function(event) {
                if (event) event.preventDefault();
                this.selectedMenuItem = false;
            },

            editMenuItem: function (menuItem) {
                menuItem.open_in_new_tab = menuItem.open_in_new_tab ? menuItem.open_in_new_tab : false;
                this.selectedMenuItem = menuItem;
            },

            removeMenuItem: function (menuItem) {
                let _this = this;
                swal({
                    title: "Menu item verwijderen?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder dit menu item",
                }).then(function(){
                    _this.doRemoveMenuItem(menuItem);
                }).done();
            },

            doRemoveMenuItem: function (menuItem) {
                let _this = this;
                $.ajax({
                    url: '/cms/menu/'+menuItem.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(result) {
                        _this.loadFromDatabase();
                        _this.notifyMenuUpdate();
                    }
                });
            },

            isLink: function(menuItem) {
                return menuItem.link !== null && menuItem.link.length > 0;
            },

            submitForm: function () {
                let request = new Request('/cms/menu');
                request.setForm('#menuForm');
                request.setType('POST');

                request.addFields(['page_id', 'link', 'title']);
                request.addCheckboxes(['open_in_new_tab']);

                if (this.selectedMenuItem.id !== undefined) {
                    request.setType('PATCH');
                    request.addToUrl(this.selectedMenuItem.id);
                }

                let _this = this;
                request.send(function(data) {
                    _this.selectedMenuItem = false;
                    _this.loadMenu();
                    _this.notifyMenuUpdate();
                    if (request.getType() === 'POST') {
                        swal({
                            title: "Menu item toegevoegd!",
                            text: 'Menu item is succesvol aangemaakt.',
                            type: "success",
                            timer: 2000
                        }).done();
                    } else {
                        swal({
                            title: "Menu item aangepast!",
                            text: 'Menu item is succesvol aangepast.',
                            type: "success",
                            timer: 2000
                        }).done();
                    }
                });
            },

            saveOrder: function() {
                let array = $('.sortable-menu').nestedSortable('toArray');
                $.ajax({
                    url: '/cms/menu/order',
                    type: 'POST',
                    data: {
                        _method: 'PATCH',
                        pages: array
                    },
                }).done(() => this.notifyMenuUpdate());
            },

            notifyMenuUpdate: function() {
                this.$root.$emit('menu-update');
            },

            forceUpdate: function() {
                this.componentKey += 1;
            }
        }
    }
</script>