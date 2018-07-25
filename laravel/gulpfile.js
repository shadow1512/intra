var gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    clean = require('gulp-clean'),
    cleanCSS = require('gulp-clean-css');


gulp.task('sass', function () {
    return gulp.src('./resources/styles/sass/*.sass')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./resources/styles/css/'));
});

gulp.task('concat',function () {
    return gulp.src(['./resources/styles/css/fonts.css', './resources/styles/css/base.css', './resources/styles/css/header.css', './resources/styles/css/main.css', './resources/styles/css/dinner.css', './resources/styles/css/directory.css', './resources/styles/css/docs.css', './resources/styles/css/library.css', './resources/styles/css/order.css', './resources/styles/css/profile.css', './resources/styles/css/project.css', './resources/styles/css/cosmetic.css'])
        .pipe(concat('styles.css'))
        .pipe(autoprefixer({
            browsers: ['> 1%', 'IE 8', 'last 20 versions'],
            cascade: false
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/styles/css/'));
});
gulp.task('default', ['sass', 'concat']);
