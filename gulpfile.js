'use strict';
//var lr = require('tiny-lr');
var gulp = require('gulp');
var sass = require('gulp-sass');
//var livereload = require('gulp-livereload');
//var csso = require('gulp-csso');
//var uglify = require('gulp-uglify');


gulp.task('sass', function () {
	gulp.src('./assets/sass/**/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./assets/css'));
});
 
gulp.task('sass:watch', function () {
	gulp.watch('./assets/sass/**/*.scss', ['sass']);
});