/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

var _ = require('lodash');
var Vue = require('vue');
var bootstrap = require('bootstrap-sass');
var contenttools = require('contenttools');

Vue.component('example', require('./components/Example.vue'));

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const app = new Vue({
    el: 'body',
    data: {
        message: "hallo"
    }
});
