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
    'public_css' : 'public/css/fonts/',
    'public_plugins' : 'public/plugins/',
    'bootstrap_fonts' : 'node_modules/bootstrap-sass/assets/fonts/bootstrap',
    'contenttools' : 'node_modules/ContentTools/build',
    'sweetalert2' : 'node_modules/sweetalert2/dist',
    'awesomplete' : ['node_modules/awesomplete/awesomplete.js','node_modules/awesomplete/awesomplete.css']
};

elixir(function(mix) {

    mix.sass([
        'app.scss'
    ]).webpack('app.js')
    .webpack('libraries.js');

});
