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

var paths = {
    'public_css' : '/public/css/fonts/',
    'public_plugins' : '/public/plugins/',
    'bootstrap_fonts' : '/node_modules/bootstrap-sass/assets/fonts/bootstrap',
    'contenttools' : '/node_modules/ContentTools/build',
    'sweetalert2' : '/node_modules/sweetalert2/dist',
    'gridstack' : '/node_modules/gridstack/dist',
};

elixir(function(mix) {

    mix.sass([
        'app.scss'
    ]).webpack('app.js')
        .copy(paths.bootstrap_fonts, paths.public_css+'bootstrap')
        .copy(paths.contenttools, paths.public_plugins+'contenttools')
        .copy(paths.gridstack, paths.public_plugins+'gridstack')
        .copy(paths.sweetalert2, paths.public_plugins+'sweetalert2');

});
