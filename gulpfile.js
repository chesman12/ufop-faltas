const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

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

    // Imagens
    mix.copy('resources/assets/img', 'public/img');

    // Fontes
    mix.copy('resources/assets/fonts', 'public/fonts');

    // Plugins Javascript
    mix.copy('resources/assets/js/jQueryUI', 'public/js/plugins/jQueryUI');
    mix.copy('resources/assets/js/jQueryMask', 'public/js/plugins/jQueryMask');
    mix.copy('resources/assets/js/datatables', 'public/js/plugins/datatables');
    mix.copy('resources/assets/js/toastr', 'public/js/plugins/toastr');

    // CSS que não funcionam em conjunto
    // TODO verificar se usando SASS e LESS o erro é resolvido
    mix.copy('resources/assets/css/bootstrap', 'public/css/bootstrap');
    mix.copy('resources/assets/css/font-awesome', 'public/css/font-awesome');
    mix.copy('resources/assets/css', 'public/css');

    // Concatena todos os CSSs em um único arquivo
    mix.styles([
        'adminLTE/AdminLTE.min.css',
        'adminLTE/skins/skin-ufop.css',
        'ufop.css',
        'toastr/toastr.min.css'
    ], 'public/css/app.css', 'resources/assets/css/'); // Destino, path dos arquivos informados

    // Concatena todos os JavaScripts
    mix.scripts([
        'jQuery/jquery-2.2.4.min.js',
        'bootstrap/bootstrap.min.js',
        'adminLTE/app.min.js',
        'slimScroll/jquery.slimscroll.min.js',
        'fastclick/fastclick.min.js',
        'toastr/toastr.min.js',
        'toastr/toastr.options.js'
    ], 'public/js/app.js', 'resources/assets/js/'); // Destino, path dos arquivos informados

    // Versiona os arquivos gerados
    mix.version([
        'public/js/app.js',
        'public/css/app.css'
    ]);
});
