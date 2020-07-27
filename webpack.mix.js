const mix = require('laravel-mix');



mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({
    node: {
        fs: "empty"
    },
    resolve: {
        alias: {
            "handlebars" : "handlebars/dist/handlebars.js"
        }
    },
});
