const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.options({
    terser: {
        extractComments: false,
    }
});

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/v8n/dist/v8n.min.js',
    'node_modules/notyf/notyf.min.js'
    ], 'public/js/vendor/base.js')
    .scripts([
        'node_modules/datatables.net/js/dataTables.min.js',
        'node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js',
    ],'public/js/vendor/datatables.js')
    .scripts([
        'node_modules/just-validate/dist/just-validate.production.min.js',
        'node_modules/just-validate-plugin-date/dist/just-validate-plugin-date.production.min.js'
    ], 'public/js/vendor/validate.js')
    .copy('node_modules/@selectize/selectize/dist/js/selectize.min.js', 'public/js/vendor/selectize.js')
    .copy('node_modules/chart.js/dist/chart.umd.js', 'public/js/vendor/chart.js');

mix.js('resources/js/app/receipts/create/main.js', 'public/js/app/receipts/create.js')
    .js('resources/js/app/closure/daily/index.js', 'public/js/app/closure/daily/index.js')
    .js('resources/js/app/closure/yearly/index.js', 'public/js/app/closure/yearly/index.js')
    .js('resources/js/app/customers/list.js', 'public/js/app/customers/list.js')
    .js('resources/js/app/customers/create.js', 'public/js/app/customers/create.js')
    .js('resources/js/app/expenses/list.js', 'public/js/app/expenses/list.js')
    .js('resources/js/app/expenses/create.js', 'public/js/app/expenses/create.js')
    .js('resources/js/app/offers/list.js', 'public/js/app/offers/list.js')
    .js('resources/js/app/offers/create.js', 'public/js/app/offers/create.js')
    .js('resources/js/app/rates/index.js', 'public/js/app/rates/index.js')
    .js('resources/js/app/rates/create.js', 'public/js/app/rates/create.js')
    .js('resources/js/app/rates/exceptions/index.js', 'public/js/app/rates/exceptions/index.js')
    .js('resources/js/app/rates/exceptions/create.js', 'public/js/app/rates/exceptions/create.js')
    .js('resources/js/app/receipts/list.js', 'public/js/app/receipts/list.js')
    .js('resources/js/app/report/age/index.js', 'public/js/app/report/age/index.js')
    .js('resources/js/app/report/alternatives/index.js', 'public/js/app/report/alternatives/index.js')
    .js('resources/js/app/report/customersYearly/index.js', 'public/js/app/report/customersYearly/index.js')
    .js('resources/js/app/report/deceased/index.js', 'public/js/app/report/deceased/index.js')
    .js('resources/js/app/report/estimation/index.js', 'public/js/app/report/estimation/index.js')
    .js('resources/js/app/report/late/index.js', 'public/js/app/report/late/index.js')
    .js('resources/js/app/report/new/index.js', 'public/js/app/report/new/index.js')
    .js('resources/js/app/report/revocated/index.js', 'public/js/app/report/revocated/index.js');

mix.sass('resources/sass/app.scss', 'public/css/base.css')
    .sass('resources/sass/datatables.scss', 'public/css/vendor/datatables.css')
    .copy('resources/css/tables.css', 'public/css/tables.css')
    .copy('resources/css/yearly.css', 'public/css/yearly.css')
    .copy('node_modules/@selectize/selectize/dist/css/selectize.css', 'public/css/vendor/selectize.css')
    .copy('node_modules/@selectize/selectize/dist/css/selectize.bootstrap5.css',
        'public/css/vendor/selectize.bootstrap5.css')

if (mix.inProduction()) {
    mix.version();
}
