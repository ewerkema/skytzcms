/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

window.Vue = require('vue');
require('vue-events');

const moment = require('moment');
require('moment/locale/nl');

Vue.use(require('vue-moment'), {
    moment: moment
});

Vue.component('example', require('./components/Example.vue'));
Vue.component('list-articles', require('./components/ListArticles.vue'));
Vue.component('list-albums', require('./components/ListAlbums.vue'));
Vue.component('list-sliders', require('./components/ListSliders.vue'));
Vue.component('list-forms', require('./components/ListForms.vue'));
Vue.component('list-headers', require('./components/ListHeaders.vue'));
Vue.component('list-media', require('./components/ListMedia.vue'));
Vue.component('list-html-blocks', require('./components/ListHtmlBlocks.vue'));
Vue.component('list-projects', require('./components/ListProjects.vue'));
Vue.component('list-users', require('./components/ListUsers.vue'));
Vue.component('list-social', require('./components/ListSocial.vue'));
Vue.component('select-media', require('./components/SelectMedia.vue'));
Vue.component('select-media-with-edit', require('./components/SelectMediaWithEdit.vue'));
Vue.component('select-module', require('./components/SelectModule.vue'));

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
});
