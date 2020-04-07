<template>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading flex-row justify-space-between align-items-center">
                    <h4>Selecteer pagina</h4>
                    <button class="btn btn-default btn-sm" @click.prevent="load">
                        <span class="glyphicon glyphicon-refresh"></span>
                    </button>

                </div>
                <div class="panel-body">
                    <div class="input-group" style="margin-bottom: 15px;">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                        <input type="text" class="form-control" placeholder="Pagina zoeken..." v-model="search">
                    </div>
                    <Tree id="pageTree" ref="my-tree-ref" :custom-styles="treeStyles" :custom-options="treeOptions" :nodes="data"></Tree>

                    <div class="flex-row justify-space-between">
                        <button class="btn btn-info" @click.prevent="editMenu"><span class="glyphicon glyphicon-sort"></span> Menu indelen</button>
                        <button class="btn btn-success" @click.prevent="addPage"><span class="glyphicon glyphicon-plus"></span> Pagina</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="selectedPage === false">
            <p><em>Selecteer een pagina in het menu links om een pagina te bewerken.</em></p>
        </div>
        <div class="col-md-8" v-else>
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
                        <select class="form-control" id="selectedPageHeader" name="header_id">
                            <option value="0">Geen header</option>
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
                <button class="btn btn-info right" @click.prevent="">Toevoegen aan menu</button>
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
        },

        data() {
            return {
                pages: [],
                menu: [],
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
                            height: '35px'
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
                    addNode: { state: true, fn: this.addPage, appearOnHover: true },
                    editNode: { state: true, fn: this.editPage, appearOnHover: true },
                    deleteNode: { state: true, fn: this.deletePage, appearOnHover: true },
                }
            }
        },

        computed: {
            filterPagesSearch() {
                return _.filter(this.pages, (page) => {
                    let searchTerm = this.search.toLowerCase();
                    return page.title.toLowerCase().includes(searchTerm)
                        || page.meta_title.toLowerCase().includes(searchTerm)
                        || page.meta_desc.toLowerCase().includes(searchTerm);
                });
            },

            menuPages() {
                return _.filter(this.filterPagesSearch, (page) => this.pageInMenu(page));
            },

            nonMenuPages() {
                return _.filter(this.filterPagesSearch, (page) => !this.pageInMenu(page))
            },

            data() {
                return [
                    {
                        text: 'In navigatie menu',
                        selectable: false,
                        disabled: true,
                        state: {
                            expanded: true,
                            editable: true,
                        },
                        nodes: _.sortBy(_.map(this.menuPages, this.toList), (page) => page.order),
                    },
                    {
                        text: 'Losse pagina\'s',
                        selectable: false,
                        disabled: true,
                        state: {
                            expanded: true,
                            editable: false,
                        },
                        nodes: _.map(this.nonMenuPages, this.toList)
                    }
                ];
            }
        },

        methods: {
            loadFromDatabase() {
                $.get('/cms/menu', (data) => this.menu = data.menu);

                return $.get('/cms/pages', (data) => this.pages = data);
            },

            toList(page) {
                return {
                    id: page.id,
                    text: page.title,
                    slug: page.slug,
                }
            },

            pageInMenu(page) {
                return _.find(this.menu, ['page_id', page.id]);
            },

            editMenu() {
                $('#menuModal').modal('show');
            },

            getMenuId(page) {
                let menuItem = this.pageInMenu(page);
                return menuItem !== undefined ? menuItem.id : 0;
            },

            findPage(pageId) {
                return _.find(this.pages, ['id', pageId]);
            },

            addPage(state = false) {
                let visibleInMenu = $('[name=menu]');
                if (this.pageInMenu(state) || state.text === 'In navigatie menu') {
                    visibleInMenu.prop('checked', true);
                } else {
                    visibleInMenu.prop('checked', false);
                }

                let subpageSelect = $('[name=parent_id]');
                if (this.pageInMenu(state)) {
                    subpageSelect.val(this.getMenuId(state));
                } else {
                    subpageSelect.val(subpageSelect.find('option:first').val());
                }

                $('#newPageModal').modal('show');
            },

            editPage(state) {
                let page = this.findPage(state.id);

                if (page !== undefined) {
                    this.selectedPage = page;
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
                    swal({
                        title: "Kan dit item niet verwijderen!",
                        type: "warning",
                        timer: 2000
                    }).done();
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

            getBaseUrl() {
                let getUrl = window.location;
                return getUrl.protocol + "//" + getUrl.host;
            }
        }
    }
</script>