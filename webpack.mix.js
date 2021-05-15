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
    .js('resources/js/vue/index.js', 'public/js/vue')
    .js('resources/js/vue/maintenances/replace_tire.js', 'public/js/vue')
    .js('resources/js/vue/vehicle_settings.js', 'public/js/vue')
    .js('resources/js/vue/maintenance_inventories.js', 'public/js/vue')
    .js('resources/js/vue/create.js', 'public/js/vue')
    .js('resources/js/vue/dashboard.js', 'public/js/vue')
    // .extract(['vue', 'bootstrap'])
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/landing-icon.scss', 'public/css')
    .sass('resources/sass/material-dashboard-liff.scss', 'public/material/css', {
        implementation: require('node-sass'),
    })
    .js('resources/js/vue/liff.js', 'public/js/vue')
    .js('resources/js/vue/liff_example.js', 'public/js/vue')
    // .sass('resources/sass/material-dashboard.scss', 'public/material/css', {
    //     implementation: require('node-sass'),
    // })
    .sass('resources/sass/syncfusion.scss', 'public/css', {
        implementation: require('node-sass'),
        includePaths: [
            path.resolve(__dirname, './node_modules/@syncfusion/')
        ]
    })
    .scripts([
        'resources/js/google-chart.js',
    //     // 'resources/js/form.js',
        // 'resources/js/material/plugins/perfect-scrollbar.jquery.min.js',
        // 'resources/js/material/plugins/moment.min.js',
    //     // 'resources/js/material/plugins/sweetalert2.js',
    //     // 'resources/js/material/plugins/jquery.validate.min.js',
    //     // 'resources/js/material/plugins/jquery.bootstrap-wizard.js',
    //     // 'resources/js/material/plugins/bootstrap-selectpicker.js',
    //     // 'resources/js/material/plugins/bootstrap-datetimepicker.min.js',
    //     // 'resources/js/material/plugins/jquery.dataTables.min.js',
    //     // 'resources/js/material/plugins/bootstrap-tagsinput.js',
    //     // 'resources/js/material/plugins/jasny-bootstrap.min.js',
    //     // 'resources/js/material/plugins/fullcalendar.min.js',
    //     // 'resources/js/material/plugins/jquery-jvectormap.js',
    //     // 'resources/js/material/plugins/nouislider.min.js',
    //     // 'resources/js/material/cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js',
    //     // 'resources/js/material/plugins/arrive.min.js',
    //     // 'resources/js/material/plugins/chartist.min.js',
    //     // 'resources/js/material/plugins/bootstrap-notify.js',
    //     // 'resources/js/material/material-dashboard.js',
    //     // 'resources/js/material/demo.js',
    //     // 'resources/js/material/settings.js',
    ], 'public/js/before.js')
    .scripts([
        'resources/js/material/core/jquery.min.js',
        'resources/js/material/core/popper.min.js',
        'resources/js/material/core/bootstrap-material-design.min.js',
        'resources/js/black-dashboard.js',
        'resources/js/loader.js',
    ], 'public/js/after.js')
    .scripts([
        'resources/js/material/core/jquery.min.js',
        'resources/js/material/core/popper.min.js',
        'resources/js/material/plugins/bootstrap-notify.js',
    ], 'public/material/js/liff.js')
    .scripts([
        'resources/js/landing/jquery-1.11.2.min.js',
        'resources/js/landing/bootstrap.js',
        'resources/js/landing/jquery.waypoints.min.js',
        'resources/js/landing/modernizr.js',
        'resources/js/landing/rubick.js',
    ], 'public/js/landing.js')
    .styles([
        'resources/css/syncfusion/inputs/material.css',
        'resources/css/bootstrap/grid.css'
    ], 'public/css/theme.css')
    .styles([
        'resources/css/landing/bootstrap.css',
        'resources/css/landing/rubick.css',
    ], 'public/css/landing.css')
    .styles([
        'resources/css/landing/fonts/pe-icon-7-stroke.css',
        'resources/css/landing/fonts/Rubik-Fonts.css',
    ], 'public/css/landing-font.css')
    .sass('resources/sass/black-dashboard.scss', 'public/css', {
        implementation: require('node-sass'),
    })
    .version();
