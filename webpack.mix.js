const mix = require('laravel-mix');
const WebpackMildCompile = require('webpack-mild-compile').Plugin;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
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


mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/libraries.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .webpackConfig({plugins: [new WebpackMildCompile()]});