var gulp = require('gulp');
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.sass('app.scss');
});

gulp.task("copyfiles", function () {
    var publicDest = ['public/js', 'public/css', 'public/fonts'];
    var assetsCopy = [
        //jsCopyPath
        ['vendor/bower_dl/jquery/dist/jquery.min.js', publicDest[0]],
        ['vendor/bower_dl/metisMenu/dist/metisMenu.min.js', publicDest[0]],
        ['vendor/bower_dl/bootstrap/dist/js/bootstrap.min.js', publicDest[0]],
        ['vendor/bower_dl/datatables/media/js/jquery.dataTables.min.js', publicDest[0]],
        ['vendor/bower_dl/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js', publicDest[0]],
        ['vendor/bower_dl/datatables.net-select/js/dataTables.select.min.js', publicDest[0]],
        ['vendor/bower_dl/sb-admin-2/js/sb-admin-2.js', publicDest[0]],
        ['vendor/bower_dl/jquery-bar-rating/dist/jquery.barrating.min.js', publicDest[0]],
        ['vendor/bower_dl/datatables.net-buttons/js/dataTables.buttons.min.js', publicDest[0]],
        ['vendor/bower_dl/jquery-serialize-object/dist/jquery.serialize-object.min.js', publicDest[0]],
        ['vendor/bower_dl/jquery-form/jquery.form.js', publicDest[0]],
        ['vendor/bower_dl/EasyAutocomplete/dist/jquery.easy-autocomplete.min.js', publicDest[0]],
        //cssCopyPath
        ['vendor/bower_dl/metisMenu/dist/metisMenu.min.css', publicDest[1]],
        ['vendor/bower_dl/bootstrap/dist/css/bootstrap.min.css', publicDest[1]],
        ['vendor/bower_dl/bootstrap/dist/css/bootstrap.min.css.map', publicDest[1]],
        ['vendor/bower_dl/font-awesome/css/font-awesome.min.css', publicDest[1]],
        ['vendor/bower_dl/datatables/media/css/jquery.dataTables.min.css', publicDest[1]],
        ['vendor/bower_dl/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css', publicDest[1]],
        ['vendor/bower_dl/datatables.net-select-dt/css/select.dataTables.min.css', publicDest[1]],
        ['vendor/bower_dl/sb-admin-2/css/sb-admin-2.css', publicDest[1]],
        ['vendor/bower_dl/jquery-bar-rating/dist/themes/fontawesome-stars.css', publicDest[1]],
        ['vendor/bower_dl/datatables.net-buttons-dt/css/buttons.dataTables.min.css', publicDest[1]],
        ['vendor/bower_dl/EasyAutocomplete/dist/easy-autocomplete.min.css', publicDest[1]],
        //fontsCopyPath
        ['vendor/bower_dl/bootstrap/dist/fonts/**', publicDest[2]],
        ['vendor/bower_dl/font-awesome/fonts/**', publicDest[2]],
        //handleJsCssPath
        ['resources/assets/css/**', publicDest[1]],
        ['resources/assets/js/**', publicDest[0]],
    ];

    for (var i = 0; i < assetsCopy.length; i++) {
        gulp.src(assetsCopy[i][0]).pipe(gulp.dest(assetsCopy[i][1]));
    }
});
