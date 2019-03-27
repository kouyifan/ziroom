const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

var public_path  = '../../public';

mix.setPublicPath(public_path).mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'ziroom/js/app.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'ziroom/css/app.css')
    .sass( __dirname + '/Resources/assets/sass/home.scss', 'ziroom/css/home.css');

mix.copy( __dirname + '/Resources/assets/images', public_path + '/ziroom/images/');
mix.copy( __dirname + '/Resources/assets/extends', public_path + '/ziroom/extends/');

mix.version();

if (mix.inProduction()) {
    mix.version();
}