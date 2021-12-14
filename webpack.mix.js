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
// mix.js('resources/js/ppic/jadwal/app.js', 'public/native/js/ppic/jadwal.js').vue()
// mix.js('resources/js/ppic/data/app.js', 'public/native/js/ppic/data.js').vue()
// mix.js('resources/js/ppic/dashboard/app.js', 'public/native/js/ppic/dashboard.js').vue()
// mix.js('resources/js/manager/app.js', 'public/native/js/ppic/manager.js').vue()


// mix.js('resources/js/gbj/stok/app.js', 'public/native/js/gbj/stok.js').vue()
// mix.js('resources/js/gbj/penjualan/app.js', 'public/native/js/gbj/penjualan.js').vue()
// mix.js('resources/js/gbj/so/app.js', 'public/native/js/gbj/so.js').vue()
// mix.js('resources/js/penjualan/produk/app.js', 'public/native/js/penjualan/produk.js').vue()
// mix.js('resources/js/penjualan/customer/app.js', 'public/native/js/penjualan/customer.js').vue()
// mix.js('resources/js/penjualan/penjualan/app.js', 'public/native/js/penjualan/penjualan.js').vue()
// mix.js('resources/js/penjualan/po/app.js', 'public/native/js/penjualan/po.js').vue()