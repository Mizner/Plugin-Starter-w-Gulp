var theProject = "projectname"; // Make sure this matches the project name in index.php
var theSite = theProject + '.dev'; //also a good idea to match your localhost url

var jsFilePath = 'assets/scripts/';
var theFiles = orderJsFiles([
    // 1st file loads first, 2nd, ect.
    'main.js',
    'create-form.js'
]);

function orderJsFiles(arr){
    return arr.map(function(str){
        return jsFilePath + str
    })
}

var gulp = require('gulp');
var babel = require('gulp-babel');
var browserSync = require('browser-sync').create();
var autoprefixer = require('gulp-autoprefixer');
var cssnano = require('gulp-cssnano');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('sass', function () {
    gulp.src('./assets/styles/main.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(cssnano())
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sourcemaps.write(''))
        .pipe(rename(theProject + '.min.css'))
        .pipe(gulp.dest('./dist'))
        .pipe(browserSync.stream());
});



gulp.task('js', function () {
    gulp.src(theFiles)
        .pipe(concat('output.min.js')) // concat pulls all our files together before minifying them
        .pipe(babel({
            presets: ['es2015']
        }))
        .pipe(uglify())
        .pipe(rename(theProject + '.min.js'))
        .pipe(gulp.dest('./dist'))
});

gulp.task('browser-sync', function () {
    browserSync.init(['*'], {
        proxy: theSite,
        root: [__dirname],
        open: {
            file: 'index.php'
        }
    });
});

gulp.task('watch', ['browser-sync'], function () {
    gulp.watch('./assets/styles/**/*.scss', ['sass']);
    gulp.watch('./assets/scripts/**/*.js', ['js']);
    gulp.watch('./assets/scripts/**/*.js', browserSync.reload);
    gulp.watch('**/*.php', browserSync.reload);
});

gulp.task('default', ['sass', 'js']);