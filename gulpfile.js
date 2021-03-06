var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var pkg = require('./package.json');

// Copy vendor files from /node_modules into /vendor
// NOTE: requires `npm install` before running!
gulp.task('copy', function() {
  gulp.src([
      'node_modules/bootstrap/dist/**/*',
      '!**/npm.js',
      '!**/bootstrap-theme.*',
      '!**/*.map'
    ])
    .pipe(gulp.dest('web/assets/vendor/bootstrap'))

  gulp.src(['node_modules/jquery/dist/jquery.js', 'node_modules/jquery/dist/jquery.min.js'])
    .pipe(gulp.dest('web/assets/vendor/jquery'))
})

// Default task
gulp.task('default', ['copy']);

// Configure the browserSync task
gulp.task('browserSync', function() {
  browserSync.init({
    server: {
      baseDir: ''
    },
  })
})

// Dev task with browserSync
gulp.task('dev', ['browserSync'], function() {
  // Reloads the browser whenever HTML or CSS files change
  gulp.watch('css/*.css', browserSync.reload);
  gulp.watch('*.html', browserSync.reload);
});

var gulp = require('gulp');
var less = require('gulp-less');
var browserSync = require('browser-sync').create();
var header = require('gulp-header');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var pkg = require('./package.json');

// Set the banner content
var banner = ['/*!\n',
    ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
    ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
    ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n',
    ' */\n',
    ''
].join('');

// Compile LESS files from /less into /css
gulp.task('less', function() {
    return gulp.src('web/assets/less/sb-admin-2.less')
        .pipe(less())
        .pipe(header(banner, { pkg: pkg }))
        .pipe(gulp.dest('web/assets/dist/css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// Minify compiled CSS
gulp.task('minify-css', ['less'], function() {
    return gulp.src('web/assets/dist/css/sb-admin-2.css')
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('web/assets/dist/css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// Copy JS to dist
gulp.task('js', function() {
    return gulp.src(['web/assets/js/sb-admin-2.js'])
        .pipe(header(banner, { pkg: pkg }))
        .pipe(gulp.dest('web/assets/dist/js'))
        .pipe(browserSync.reload({
            stream: true
        }))
})

// Minify JS
gulp.task('minify-js', ['js'], function() {
    return gulp.src('web/assets/js/sb-admin-2.js')
        .pipe(uglify())
        .pipe(header(banner, { pkg: pkg }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('web/assets/dist/js'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// Copy vendor libraries from /bower_components into /vendor
// gulp.task('copy', function() {
//     gulp.src(['bower_components/bootstrap/dist/**/*', '!**/npm.js', '!**/bootstrap-theme.*', '!**/*.map'])
//         .pipe(gulp.dest('web/assets/vendor/bootstrap'))
//
//     gulp.src(['bower_components/bootstrap-social/*.css', 'bower_components/bootstrap-social/*.less', 'bower_components/bootstrap-social/*.scss'])
//         .pipe(gulp.dest('web/assets/vendor/bootstrap-social'))
//
//     gulp.src(['bower_components/datatables/media/**/*'])
//         .pipe(gulp.dest('web/assets/vendor/datatables'))
//
//     gulp.src(['bower_components/datatables-plugins/integration/bootstrap/3/*'])
//         .pipe(gulp.dest('web/assets/vendor/datatables-plugins'))
//
//     gulp.src(['bower_components/datatables-responsive/css/*', 'bower_components/datatables-responsive/js/*'])
//         .pipe(gulp.dest('web/assets/vendor/datatables-responsive'))
//
//     gulp.src(['bower_components/flot/*.js'])
//         .pipe(gulp.dest('web/assets/vendor/flot'))
//
//     gulp.src(['bower_components/flot.tooltip/js/*.js'])
//         .pipe(gulp.dest('web/assets/vendor/flot-tooltip'))
//
//     gulp.src(['bower_components/font-awesome/**/*', '!bower_components/font-awesome/*.json', '!bower_components/font-awesome/.*'])
//         .pipe(gulp.dest('web/assets/vendor/font-awesome'))
//
//     gulp.src(['bower_components/jquery/dist/jquery.js', 'bower_components/jquery/dist/jquery.min.js'])
//         .pipe(gulp.dest('web/assets/vendor/jquery'))
//
//     gulp.src(['bower_components/metisMenu/dist/*'])
//         .pipe(gulp.dest('web/assets/vendor/metisMenu'))
//
//     gulp.src(['bower_components/morrisjs/*.js', 'bower_components/morrisjs/*.css', '!bower_components/morrisjs/Gruntfile.js'])
//         .pipe(gulp.dest('web/assets/vendor/morrisjs'))
//
//     gulp.src(['bower_components/raphael/raphael.js', 'bower_components/raphael/raphael.min.js'])
//         .pipe(gulp.dest('web/assets/vendor/raphael'))
//
// })

// Run everything
gulp.task('default', ['minify-css', 'minify-js', 'copy']);

// Configure the browserSync task
gulp.task('browserSync', function() {
    browserSync.init({
        server: {
            baseDir: ''
        },
    })
})

// Dev task with browserSync
gulp.task('dev', ['browserSync', 'less', 'minify-css', 'js', 'minify-js'], function() {
    gulp.watch('less/*.less', ['less']);
    gulp.watch('dist/css/*.css', ['minify-css']);
    gulp.watch('js/*.js', ['minify-js']);
    // Reloads the browser whenever HTML or JS files change
    gulp.watch('pages/*.html', browserSync.reload);
    gulp.watch('dist/js/*.js', browserSync.reload);
});