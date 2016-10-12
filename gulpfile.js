'use strict';
//var lr = require('tiny-lr');
var gulp = require('gulp');
var sass = require('gulp-sass');
//var livereload = require('gulp-livereload');
//var csso = require('gulp-csso');
//var uglify = require('gulp-uglify');


gulp.task('sass', function () {
	gulp.src('./design/sass/**/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./design/css'));
});
 
gulp.task('sass:watch', function () {
	gulp.watch('./design/sass/**/*.scss', ['sass']);
});