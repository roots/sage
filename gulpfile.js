// Gulp
var gulp = require('gulp');

// Sass/CSS stuff
var sass = require('gulp-sass');
var prefix = require('gulp-autoprefixer');
var minifycss = require('gulp-minify-css');
var concat = require('gulp-concat');

// JavaScript
var uglify = require('gulp-uglify');

// Images
var svgmin = require('gulp-svgmin');
var imagemin = require('gulp-imagemin');

// Stats and Things
var size = require('gulp-size');
var rename = require('gulp-rename');
var util = require('gulp-util');

//

// compile all your Sass from Foundation and app.scss
	gulp.task('sass', function (){
		gulp.src(['bower_components/foundation/scss/normalize.scss', 'bower_components/foundation/scss/foundation.scss', 'assets/scss/app.scss'])
			.pipe(sass({style: 'compressed'}))
			.pipe(concat('main.css'))
			.pipe(rename({suffix: '.min'}))
			.pipe(minifycss())
			.pipe(gulp.dest('assets/css/'));
			util.log('Sass compiled & stored.');
	});

// Uglify JS
	gulp.task('uglify', function(){
		gulp.src([
			'bower_components/jquery/dist/jquery.min.js',
			'bower_components/modernizr/modernizr.js',
			'bower_components/foundation/js/foundation.min.js',
			'bower_components/foundation/js/foundation/foundation.offcanvas.js',
			'assets/js/_*.js'])
			.pipe(concat('scripts.js'))
			.pipe(rename({suffix: '.min'}))
			.pipe(uglify())
			.pipe(gulp.dest('assets/js/'));
			util.log('Javascript compiled and minified');
	});


// Images
	gulp.task('svgmin', function() {
		gulp.src('assets/img/svg/*.svg')
		.pipe(svgmin())
		.pipe(gulp.dest('assets/img/svg'));
		util.log('SVG images minified');
	});

	gulp.task('imagemin', function () {
		gulp.src('assets/img/*')
		.pipe(imagemin())
		.pipe(gulp.dest('assets/img'));
		util.log('Images minified');
	});







gulp.task('default', function(){

	// watch me getting Sassy
	gulp.watch("assets/scss/**/*.scss", function(event){
		gulp.run('sass');
	});
	// make my JavaScript ugly
	gulp.watch("assets/js/_*.js", function(event){
		gulp.run('uglify');
	});
	// images
	gulp.watch("assets/img/**/*", function(event){
		gulp.run('imagemin');
		gulp.run('svgmin');
	});
});