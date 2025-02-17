import { build, defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/datatables.js',
                'resources/js/validate.js',
                'resources/js/selectize.js',
                'resources/js/chart.js',
                'resources/js/app/closure/daily/index.js',
                'resources/js/app/closure/yearly/index.js',
                'resources/js/app/customers/create.js',
                'resources/js/app/customers/list.js',
                'resources/js/app/customers/summary.js',
                'resources/js/app/deliveries/index.js',
                'resources/js/app/deliveries/save.js',
                'resources/js/app/expenses/create.js',
                'resources/js/app/expenses/list.js',
                'resources/js/app/offers/create.js',
                'resources/js/app/offers/list.js',
                'resources/js/app/rates/create.js',
                'resources/js/app/rates/index.js',
                'resources/js/app/rates/exceptions/create.js',
                'resources/js/app/rates/exceptions/index.js',
                'resources/js/app/receipts/create/main.js',
                'resources/js/app/receipts/list.js',
                'resources/js/app/report/age/index.js',
                'resources/js/app/report/alternatives/index.js',
                'resources/js/app/report/customersYearly/index.js',
                'resources/js/app/report/deceased/index.js',
                'resources/js/app/report/estimation/index.js',
                'resources/js/app/report/late/index.js',
                'resources/js/app/report/new/index.js',
                'resources/js/app/report/revocated/index.js',
                'resources/sass/app.scss',
                'resources/sass/datatables.scss',
                'resources/sass/selectize.scss',
                'resources/css/tables.css',
                'resources/css/yearly.css'
            ],
            buildDirectory: 'build',
            refresh: true,
        }),
    ],
});
