const mix = require('laravel-mix');

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

// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');

// plugin library
mix.js('resources/js/bootstrap.js', 'public/native/js/plugin.js')

// ppic
mix.js('resources/js/ppic/gbmp/app.js', 'public/native/js/ppic/gbmp.js').vue()
mix.js('resources/js/ppic/jadwal/app.js', 'public/native/js/ppic/jadwal.js').vue()
mix.js('resources/js/gbj/stok/app.js', 'public/native/js/gbj/stok.js').vue()
mix.js('resources/js/gbj/penjualan/app.js', 'public/native/js/gbj/penjualan.js').vue()
mix.js('resources/js/penjualan/produk/app.js', 'public/native/js/penjualan/produk.js').vue()

