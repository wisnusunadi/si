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
mix.sass('resources/css/bootstrap.scss', 'public/native/css/plugin.css')

// ppic
mix.js('resources/js/gbj/stok/app.js', 'public/native/js/gbj/stok.js').vue()
mix.js('resources/js/gbj/penjualan/app.js', 'public/native/js/gbj/penjualan.js').vue()
mix.js('resources/js/penjualan/produk/app.js', 'public/native/js/penjualan/produk.js').vue()
mix.js('resources/js/penjualan/customer/app.js', 'public/native/js/penjualan/customer.js').vue()
mix.js('resources/js/penjualan/penjualan/app.js', 'public/native/js/penjualan/penjualan.js').vue()
mix.js('resources/js/penjualan/po/app.js', 'public/native/js/penjualan/po.js').vue()

