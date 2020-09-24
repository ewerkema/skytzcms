<template>
    <div>
        <div class="col-md-12" v-if="selectedPage === false">
            <div class="flex-row justify-space-between align-items-center">
                <h4>Selecteer pagina</h4>
                <button class="btn btn-default btn-sm" @click.prevent="load">
                    <span class="glyphicon glyphicon-refresh"></span>
                </button>
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <input type="text" class="form-control" placeholder="Pagina zoeken..." v-model="search">
            </div>

            <div class="flex-row justify-space-between">
                <button class="btn btn-info" @click.prevent="editMenu"><span class="glyphicon glyphicon-sort"></span> Menu indelen</button>
                <button class="btn btn-success" @click.prevent="addPage(0)"><span class="glyphicon glyphicon-plus"></span> Pagina</button>
            </div>

            <div class="flex-row justify-space-between">
                <div>
                    <h4>Pagina's in menu ({{ menuPages.length }})</h4>
                    <Tree id="pageTree" ref="my-tree-ref" :custom-styles="treeStyles" :custom-options="treeOptions" :nodes="menuPagesList"></Tree>
                </div>
                <div>
                    <h4>Losse pagina's ({{ nonMenuPages.length }})</h4>
                    <Tree id="pageTree2" ref="my-tree-ref2" :custom-styles="treeStyles" :custom-options="treeOptions" :nodes="nonMenuPagesList"></Tree>
                </div>
            </div>
        </div>
        <div class="col-md-12" v-else>
            <h3>Pagina bewerken: {{ selectedPage.title }}</h3>

            <form action="#" class="form-horizontal" id="editPageForm">
                <div class="alert form-message" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <label for="selectedPageTitle" class="col-md-3 control-label">Pagina naam</label>

                    <div class="col-md-8">
                        <input type="text" name="title" class="form-control page-title-listener" placeholder="Pagina naam" v-model="selectedPage.title" id="selectedPageTitle" required autofocus />
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectedPageHeader" class="col-md-3 control-label">Pagina header</label>

                    <div class="col-md-8">
                        <select class="form-control" id="selectedPageHeader" name="header_id" v-model="selectedPage.header_id">
                            <option value="0">Geen header</option>
                            <option :value="header.id" v-for="header in headers">{{ header.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectedPageSlug" class="col-md-3 control-label">Pagina link</label>

                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon" id="page-url">{{ baseUrl }}</span>
                            <input type="text" name="slug" class="form-control page-slug-listener" id="selectedPageSlug" v-model="selectedPage.slug" aria-describedby="page-url" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectedPageMetaTitle" class="col-md-3 control-label">Pagina titel</label>

                    <div class="col-md-8">
                        <input type="text" name="meta_title" class="form-control" id="selectedPageMetaTitle" v-model="selectedPage.meta_title" placeholder="Pagina titel" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectedPageMetaDesc" class="col-md-3 control-label">Pagina beschrijving</label>

                    <div class="col-md-8">
                        <textarea name="meta_desc" class="form-control" id="selectedPageMetaDesc" v-model="selectedPage.meta_desc" placeholder="Pagina beschrijving"></textarea>
                    </div>
                </div>

                <button class="btn btn-success right" @click.prevent="savePage">Pagina opslaan</button>
                <button class="btn btn-default right" @click.prevent="cancelPage">Annuleren</button>
            </form>
        </div>
    </div>
</template>

<style>
    #pageTree ul {
        margin-left: 0;
    }

    #pageTree .tree-indent {
        margin: 0 5px;
    }

    #editPageForm button {
        margin-left: 15px;
    }

    #pageManagerModal .flex-row .btn {
        margin-bottom: 15px;
    }
</style>

<script>
    import Tree from 'vuejs-tree';
    import AutoloadModal from './AutoloadModal.vue';

    const NO_MENU = 0;
    const MENU_PAGE = 1;
    const MENU_LINK = 2;
    const MENU_GENERAL_TEXT = 3;

    export default {
        extends: AutoloadModal,

        components: { Tree },

        props: {
            url: {
                type: String,
                default: "",
            }
        },

        created() {
            if (this.url.length === 0) {
                this.baseUrl = this.getBaseUrl() + '/';
            } else {
                this.baseUrl = this.url;
            }

            this.$root.$on('menu-update', this.load);
            $('#newPageModal').on('hidden.bs.modal', () => this.load());
        },

        data() {
            return {
                pages: [],
                menu: [],
                menuItems: [],
                headers: [],
                selectedPage: false,
                search: "",
                baseUrl: "",
                treeStyles: {
                    tree: {
                        height: 'auto',
                        overflowY: 'visible',
                        display: 'inline-block'
                    },
                    row: {
                        width: '100%',
                        cursor: 'pointer',
                        child: {
                            height: '35px',
                        }
                    },
                },
                treeOptions: {
                    events: {
                        editableName: {
                            state: true,
                            fn: this.editPage,
                            calledEvent: null,
                        }
                    },
                    addNode: { state: true, fn: this.addNode, appearOnHover: true },
                    editNode: { state: true, fn: this.editPage, appearOnHover: true },
                    deleteNode: { state: true, fn: this.deleteNode, appearOnHover: true },
                    showTags: true,
                }
            }
        },

        computed: {
            filterPagesSearch() {
                return _.filter(this.pages, (page) => this.pageFilter(page));
            },

            filteredMenu() {
                return _.filter(this.menu, (menuItem) => this.menuItemFilter(menuItem));

            },

            menuPages() {
                return _.filter(this.filterPagesSearch, (page) => this.pageInMenu(page));
            },

            nonMenuPages() {
                return _.filter(this.filterPagesSearch, (page) => !this.pageInMenu(page))
            },

            menuPagesList() {
                return [
                    {
                        text: '[Menu]',
                        definition: MENU_GENERAL_TEXT,
                        state: {
                            expanded: true,
                        },
                        nodes: _.sortBy(_.map(this.filteredMenu, this.menuToList), (menuItem) => menuItem.order),
                    },
                ];
            },

            nonMenuPagesList() {
                return _.map(this.nonMenuPages, this.pageToList);
            },
        },

        methods: {
            loadFromDatabase() {
                $.get('/cms/menu', (data) => this.menu = data.menu);
                $.get('/cms/menu?list=true', (data) => this.menuItems = data.menu);
                $.get('/cms/headers', (data) => this.headers = data);

                return $.get('/cms/pages', (data) => this.pages = data);
            },

            menuToList(menu_item) {
                return {
                    id: menu_item.page_id,
                    menu_item_id: menu_item.id,
                    text: (menu_item.sub_items === undefined ? "- " : "") + menu_item.linkName,
                    slug: menu_item.page != null ? menu_item.page.slug : null,
                    definition: menu_item.page != null ? MENU_PAGE : MENU_LINK,
                    state: {
                        expanded: menu_item.page != null,
                        editable: menu_item.page != null,
                    },
                    nodes: _.map(menu_item.sub_items, this.menuToList),
                    tags: menu_item.page == null ? ["Losse link"] : [],
                    selectable: true,
                    checkable: true,
                }
            },

            pageToList(page) {
                return {
                    id: page.id,
                    text: page.title,
                    slug: page.slug,
                    definition: NO_MENU,
                }
            },

            pageInMenu(page) {
                return _.find(this.menuItems, ['page_id', page.id]);
            },

            editMenu() {
                $('#menuModal').modal('show');
            },

            getMenuId(page) {
                let menuItem = this.pageInMenu(page);
                if (menuItem === undefined) return 0;

                return menuItem.parent_id !== null ? menuItem.parent_id : menuItem.id;
            },

            pageFilter(page) {
                let searchTerm = this.search.toLowerCase();
                return page.title.toLowerCase().includes(searchTerm)
                    || page.meta_title.toLowerCase().includes(searchTerm)
                    || page.meta_desc.toLowerCase().includes(searchTerm);
            },

            menuItemFilter(menuItem) {
                if (menuItem.page != null) {
                    return this.pageFilter(menuItem.page) || _.find(menuItem.sub_items, (menuItem) => this.menuItemFilter(menuItem));
                }

                let searchTerm = this.search.toLowerCase();
                return menuItem.linkName.toLowerCase().includes(searchTerm) || _.find(menuItem.sub_items, (menuItem) => this.menuItemFilter(menuItem));
            },

            findPage(pageId) {
                return _.find(this.pages, ['id', pageId]);
            },

            addNode(state = false) {
                if (!this.stateIsMenuItem(state)) {
                    this.addPage(state);
                    return;
                }

                swal({
                    title: 'Toevoegen aan menu',
                    input: 'radio',
                    inputOptions: {
                        newPage: 'Nieuwe pagina',
                        addPageToMenu: 'Bestaande pagina toevoegen',
                        link: 'Losse link toevoegen',
                    },
                    inputPlaceholder: 'Selecteer een optie',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve, reject) => {
                            if (!value) {
                                reject('Je hebt nog geen optie geselecteerd!');
                            } else {
                                resolve();
                            }
                        });
                    }
                }).then((value) => {
                    switch (value) {
                        case 'newPage': this.addPage(state); break;
                        case 'addPageToMenu': this.addPageToMenu(state); break;
                        case 'link': this.addLinkToMenu(state); break;
                    }
                }).done();
            },

            addPage(state = false) {
                let newPageModal = $('#newPageModal');
                document.getElementById('newPageForm').reset();

                let visibleInMenu = $('[name=menu]');
                visibleInMenu.prop('checked', this.stateIsMenuItem(state));

                let subpageSelect = $('[name=parent_id]');
                if (state !== false && this.pageInMenu(state)) {
                    subpageSelect.val(this.getMenuId(state));
                } else {
                    subpageSelect.val(subpageSelect.find('option:first').val());
                }

                let redirectToNewPage = $('[name=redirect]');
                redirectToNewPage.prop('checked', false);

                newPageModal.modal('show');
            },

            addMenuItem(state, menuPage, data) {
                if (menuPage !== null) {
                    data.order = state.nodes.length > 0 ? null : menuPage.order;
                    data.parent_id = state.nodes.length > 0 ? menuPage.id : menuPage.parent_id;
                }

                $.post('/cms/menu', data)
                    .then(() => {
                        this.load();
                        swal({
                            title: "Pagina toegevoegd!",
                            text: 'Pagina is succesvol toegevoegd.',
                            type: "success",
                            timer: 2000
                        }).done();
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },

            addPageToMenu(state) {
                if (!this.stateIsMenuItem(state)) {
                    swal('Kan hier geen pagina toevoegen aan het menu!').done();
                    return;
                }

                let page = this.findPage(state.id);
                let menuPage = page !== undefined ? this.pageInMenu(page) : null;
                let nonMenuPages = _.filter(this.pages, (page) => !this.pageInMenu(page));

                swal({
                    title: 'Selecteer pagina',
                    input: 'select',
                    inputOptions: _.mapValues(_.keyBy(nonMenuPages, 'id'), 'title'),
                    inputPlaceholder: 'Selecteer een optie',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve, reject) => {
                            if (!value) {
                                reject('Je hebt nog geen optie geselecteerd!');
                            } else {
                                resolve();
                            }
                        });
                    }
                }).then((value) => {
                    this.doAddPageToMenu(value, state, menuPage);
                });
            },

            doAddPageToMenu(pageId, state, menuPage) {
                let data = {
                    page_id: pageId,
                    open_in_new_tab: 0
                };

                this.addMenuItem(state, menuPage, data);
            },

            addLinkToMenu(state) {
                if (!this.stateIsMenuItem(state)) {
                    swal('Kan hier geen link toevoegen aan het menu!').done();
                    return;
                }

                let page = this.findPage(state.id);
                let menuPage = page !== undefined ? this.pageInMenu(page) : null;

                swal({
                    title: 'Losse link toevoegen',
                    html:
                        '<input id="swal2-link" class="swal2-input" placeholder="Link URL">' +
                        '<input id="swal2-name" class="swal2-input" placeholder="Link naam">' +
                        '<label style="display: flex;">' +
                            '<input type="checkbox" id="swal2-opennewtab">' +
                            '<span class="swal2-label"> Openen in nieuw tabblad</span>' +
                        '</label>',
                    showCancelButton: true,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            let link = document.getElementById('swal2-link').value;
                            let title = document.getElementById('swal2-name').value;
                            let openInNewTab = document.getElementById('swal2-opennewtab').checked;

                            if (!link || !title) {
                                reject('Voer link en naam in!');
                            }

                            resolve({
                                link, title, openInNewTab,
                            });
                        });
                    },
                }).then((data) => {
                    this.doAddLinkToPage(state, data, menuPage);
                }).done();
            },

            doAddLinkToPage(state, data, menuPage) {
                data = {
                    link: data.link,
                    title: data.title,
                    open_in_new_tab: data.openInNewTab ? 1 : 0,
                };

                this.addMenuItem(state, menuPage, data);
            },

            editPage(state) {
                let page = this.findPage(state.id);

                if (page !== undefined) {
                    this.selectedPage = page;
                } else {
                    swal({
                        title: "Kan dit item niet bewerken!",
                        text: "Het geselecteerde item is geen pagina. Wil je de losse links beheren? Klik dan hieronder op \"Menu indelen\".",
                        type: "warning",
                        timer: 2000
                    }).done();
                }
            },

            savePage() {
                let request = new Request('/cms/pages/' + this.selectedPage.id + '?disableRedirect');
                request.setType('PATCH');
                request.setForm('#editPageForm');

                request.addFields(['title', 'meta_title', 'meta_desc', 'meta_keywords', 'header_id']);
                request.addField('slug', 'text', 'index');

                request.send(function(data) {
                    swal({
                        title: 'Pagina aangepast',
                        text: 'De pagina is succesvol aangepast!',
                        type: 'success',
                    })
                });

                this.selectedPage = false;
            },

            cancelPage() {
                this.selectedPage = false;
            },

            deleteNode(state) {
                switch (state.definition) {
                    case NO_MENU:
                        this.deletePage(state);
                        break;
                    case MENU_PAGE:
                        this.deletePageFromMenu(state);
                        break;
                    case MENU_LINK:
                        this.deleteLinkFromMenu(state);
                        break;
                    default:
                        this.unableToDelete();
                }
            },

            deletePage(state) {
                let page = this.findPage(state.id);

                if (page !== undefined) {
                    swal({
                        title: "Pagina verwijderen?",
                        text: `Weet je zeker dat je de pagina "${page.title}" wil verwijderen?`,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ja, verwijder deze pagina",
                    }).then(() => {
                        this.doDeletePage(page);
                    }).done();
                } else {

                }
            },

            doDeletePage(page) {
                let self = this;
                $.ajax({
                    url: '/cms/pages/' + page.id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                }).done(() => {
                    let index = self.pages.findIndex(item => item.id === page.id);
                    self.pages.splice(index, 1);
                    swal({
                        title: "Pagina verwijderd",
                        text: `Pagina "${page.title}" is succesvol verwijderd.`,
                        type: "success",
                        timer: 2000,
                    }).done();
                });
            },

            deletePageFromMenu(state) {
                let page = this.findPage(state.id);

                swal({
                    title: "Pagina uit menu verwijderen?",
                    text: `Weet je zeker dat je de pagina "${page.title}" uit het menu wil verwijderen?` + (state.nodes.length > 0 ? " Let op: Daarbij worden ook alle subpagina's uit het menu verwijderd!" : ""),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder uit menu",
                }).then(() => {
                    this.doDeleteMenuItem(state.menu_item_id);
                }).done();
            },

            deleteLinkFromMenu(state) {
                swal({
                    title: "Link uit menu verwijderen?",
                    text: `Weet je zeker dat je de link "${state.text}" uit het menu wil verwijderen?` + (state.nodes.length > 0 ? " Let op: Daarbij worden ook alle subpagina's uit het menu verwijderd!" : ""),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ja, verwijder uit menu",
                }).then(() => {
                    this.doDeleteMenuItem(state.menu_item_id);
                }).done();
            },

            doDeleteMenuItem(menuItemId) {
                $.ajax({
                    url: '/cms/menu/' + menuItemId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                }).done(() => this.load());
            },

            unableToDelete() {
                swal({
                    title: "Kan dit item niet verwijderen!",
                    type: "warning",
                    timer: 2000
                }).done();
            },

            stateIsMenuItem(state) {
                return state.definition !== NO_MENU;
            },

            getBaseUrl() {
                let getUrl = window.location;
                return getUrl.protocol + "//" + getUrl.host;
            }
        }
    }
</script>