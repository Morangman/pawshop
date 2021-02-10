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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.copyDirectory('resources/client', 'public/client');

// Common.
mix.sass('resources/common/sass/app.scss', 'public/common/css')
    .js([
            'resources/common/js/utils/notify.js',
            'resources/common/js/utils/confirmation.js',
        ],
        'public/common/js/main.js'
    );

// Admin.
mix.copyDirectory('resources/admin/css', 'public/admin/css')
    .copyDirectory('resources/admin/fonts', 'public/admin/fonts')
    .copyDirectory('resources/admin/js/main', 'public/admin/js/main');

mix.js([
        'resources/admin/js/theme.js',
        'resources/admin/js/custom.js',
        'resources/admin/js/app.js',
    ],
    'public/admin/js/main.js'
);
