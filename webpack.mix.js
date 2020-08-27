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
/*
<!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/pinterest_grid.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>  
    <!--The javascript plugin to display page loading on top-->
    <script src="{{ asset('js/plugins/pace.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/sweetalert.min.js') }}" defer></script>
    <script src="{{ asset('js/plugins/Chart.min.js') }}" defer></script>
    
*/ 
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
