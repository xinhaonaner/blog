const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

var assetsPath = 'public/assets/';

elixir(function (mix) {

    // Sass files
    mix.sass('frontend/frontend.scss', assetsPath + 'css/');
    mix.sass('backend/backend.scss', assetsPath + 'css/');

    // Copy fonts
    mix.copy(['resources/assets/fonts', 'resources/assets/talvbansal/media-manager/fonts'], assetsPath + '/fonts');

    // Version the assets
    mix.version([
            // CSS files
            assetsPath + 'css/frontend.css',
            assetsPath + 'css/backend.css',
        ]);
});
