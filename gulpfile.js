"use strict";

const gulp          = require('gulp');
const sass          = require('gulp-sass');
const autoprefixer  = require('gulp-autoprefixer');
const rename        = require('gulp-rename');
const plumber       = require('gulp-plumber');
const gutil         = require('gulp-util');
const concat        = require('gulp-concat');
const jshint        = require('gulp-jshint');
const uglify        = require('gulp-uglify');
//const imagemin      = require('gulp-imagemin');
const cleanCSS      = require('gulp-clean-css');
const browserSync   = require('browser-sync').create();

const onError = function (err) {
    console.log('An error occurred:', gutil.colors.red(err.message));
    gutil.beep(2);
    this.emit('end');
};

// Browsersync options 
const syncOpts = {
    proxy : 'dev.wordpress.localhost', 
    files : './webroot/wp-content/themes/YOUR_THEME/**/*.php', 
    // open : true, 
    // notify : true, 
    // ghostMode : true, 
    ui: { port: 8001 } 
}; 

// BrowserSync
function browser_sync(done) {
    browserSync.init(syncOpts);
    done();
}

// BrowserSync Reload
function bsync_reload(done) {
  browserSync.reload();
  done();
}

function triggerPlumber() {
    return gulp.src( src )
        .pipe( plumber() )
        .pipe( gulp.dest( url ) );
}

function css(){
    return gulp
        .src('./source/theme/sass/main.scss')
        .pipe(plumber({ errorHandler: onError }))
        .pipe(sass())
        //.pipe(autoprefixer())
        .pipe(cleanCSS())
        .pipe(gulp.dest('./webroot/wp-content/themes/YOUR_THEME/css'))
        .pipe(browserSync.stream());
};

function js() {
    return (
        gulp
            .src([
                './source/theme/js/theme.js',
                './source/theme/js/home.js'
            ])
            .pipe(jshint())
            //.pipe(jshint.reporter('default'))
            .pipe(concat('app.js'))
            .pipe(rename({suffix: '.min'}))
            //.pipe(uglify())
            .pipe(gulp.dest('./webroot/wp-content/themes/YOUR_THEME/js'))
            .pipe(browserSync.stream())
    );

};

function watch_files() {
    gulp.watch('./source/theme/sass/**/*.scss', css);
    gulp.watch('./source/theme/js/*.js', js);
    gulp.watch("./webroot/wp-content/themes/YOUR_THEME/**/*.php", bsync_reload);
};

gulp.task('css', css);
gulp.task('js', js);
gulp.task('watch', gulp.parallel(watch_files, browser_sync));

gulp.task('default', gulp.parallel(css, js, watch_files, browser_sync));