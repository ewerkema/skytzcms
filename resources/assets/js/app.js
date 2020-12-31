/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import PageManager from "./components/PageManager";

window.Vue = require('vue');
require('vue-events');

const moment = require('moment');
require('moment/locale/nl');

Vue.use(require('vue-moment'), {
    moment: moment
});

import VueEvents from 'vue-events';
Vue.use(VueEvents);

require('./forms');

import FileManager from './components/FileManager';
import MenuManager from './components/MenuManager';
import ListArticles from './components/ListArticles';
import ListAlbums from './components/ListAlbums';
import ListSliders from './components/ListSliders';
import ListForms from './components/ListForms';
import ListHeaders from './components/ListHeaders';
import ListMedia from './components/ListMedia';
import ListHtmlBlocks from './components/ListHtmlBlocks';
import ListProjects from './components/ListProjects';
import ListUsers from './components/ListUsers';
import ListSocial from './components/ListSocial';
import InsertImage from './components/InsertImage';
import SelectModule from './components/SelectModule';
import AddPageToMenu from "./components/AddPageToMenu";
import AddLinkToMenu from "./components/AddLinkToMenu";
import ListExcelTables from "./components/ListExcelTables";

Vue.component('file-manager', FileManager);
Vue.component('add-page-to-menu', AddPageToMenu);
Vue.component('add-link-to-menu', AddLinkToMenu);
Vue.component('menu-manager', MenuManager);
Vue.component('list-articles', ListArticles);
Vue.component('list-albums', ListAlbums);
Vue.component('list-sliders', ListSliders);
Vue.component('list-forms', ListForms);
Vue.component('list-headers', ListHeaders);
Vue.component('list-media', ListMedia);
Vue.component('list-html-blocks', ListHtmlBlocks);
Vue.component('list-projects', ListProjects);
Vue.component('list-users', ListUsers);
Vue.component('list-social', ListSocial);
Vue.component('list-excel-tables', ListExcelTables);
Vue.component('insert-image', InsertImage);
Vue.component('select-module', SelectModule);
Vue.component('page-manager', PageManager);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('[data-toggle="tooltip"]').tooltip();

Vue.filter('chunk', function (value, size) {
    return _.chunk(value, size);
});

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
});

Vue.filter('truncate', function (value, length) {
    if (!value) return '';
    return (value.length > value) ? value.substring(0, value) + '...' : value;
});

const app = new Vue({
    el: '#vue-app',

    created() {
        $('#websiteTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    },

    methods: {
        saveLayoutMenu: function () {
            let array = $('.sortable').nestedSortable('toArray');
            $.ajax({
                url: '/cms/pages/order',
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    pages: array
                },
                success: function() {
                    location.reload();
                }
            });
        },

        selectMedia: function() {
            $('#selectMediaModal').modal('toggle');
        },

        selectMediaWithEdit: function() {
            $('#selectMediaWithEditModal').modal('toggle');
        },
    }
});
