const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var bootstrapPath = 'node_modules/bootstrap-sass/assets';
    var contentToolsPath = 'node_modules/ContentTools';
    var sweetAlertPath = 'node_modules/sweetalert2';

    mix.sass([
        'app.scss'
    ]).webpack('app.js')
    .copy(bootstrapPath + '/fonts/bootstrap/','public/css/fonts/bootstrap')
    .copy(bootstrapPath + '/javascripts/bootstrap.min.js', 'public/js')
    .copy(contentToolsPath + '/build', 'public/plugins/contenttools')
    .copy(sweetAlertPath + '/dist', 'public/plugins/sweetalert2')
});
