var gulp = require('gulp');								// Gulp!

var sass = require('gulp-sass');						// Sass
var prefix = require('gulp-autoprefixer');				// Autoprefixr
var minifycss = require('gulp-minify-css');				// Minify CSS
var concat = require('gulp-concat');					// Concat files
var uglify = require('gulp-uglify');					// Uglify javascript
var svgmin = require('gulp-svgmin');					// SVG minify
var imagemin = require('gulp-imagemin');				// Image minify
var rename = require('gulp-rename');					// Rename files
var util = require('gulp-util');						// Writing stuff
var livereload = require('gulp-livereload');			// LiveReload
var lr = require('tiny-lr');							// tiny server
	var	server = lr();



//
//		Compile all CSS for the site
//
//////////////////////////////////////////////////////////////////////


	gulp.task('sass', function (){
		gulp.src([
			'bower_components/foundation/scss/normalize.scss',		// Gets normalize
			'assets/scss/app.scss']) 								// Gets the apps scss
			.pipe(sass({style: 'compressed'}))						// Compile sass
			.pipe(concat('main.css'))								// Concat all css
			.pipe(rename({suffix: '.min'}))							// Rename it
			.pipe(minifycss())										// Minify the CSS
			.pipe(gulp.dest('assets/css/'))							// Set the destination to assets/css
			.pipe(livereload(server));								// Reloads server
			util.log('Sass compiled & stored.');					// Output to terminal
	});





//
//		Get all the JS, concat and uglify
//
//////////////////////////////////////////////////////////////////////


	gulp.task('javascripts', function(){
		gulp.src([
			'bower_components/jquery/dist/jquery.min.js',			// Gets Jquery
			'bower_components/fastclick/lib/fastclick.js',			// Gets fastclick
			'bower_components/foundation/js/foundation.js',			// Gets Foundation (includes ALL foundation js, change to only include the scripts you'll need)
			'assets/js/_*.js'])										// Gets all the user JS _*.js from assets/js
			.pipe(concat('scripts.js'))								// Concat all the scripts
			.pipe(rename({suffix: '.min'}))							// Rename it
			.pipe(uglify())											// Uglify(minify)
			.pipe(gulp.dest('assets/js/'))							// Set destination to assets/js
			.pipe(livereload(server));								// Reloads server
			util.log('Javascript compiled and minified');			// Output to terminal
	});





//
//		Copy bower components to assets-folder
//
//////////////////////////////////////////////////////////////////////


	gulp.task('copy', function(){
		gulp.src('bower_components/modernizr/modernizr.js')			// Gets Modernizr.js
		.pipe(uglify())												// Uglify(minify)
		.pipe(rename({suffix: '.min'}))								// Rename it
		.pipe(gulp.dest('assets/js/'));								// Set destination to assets/js
		util.log('Files copied')									// Output to terminal
	});






//
//		Minify all SVGs and images
//
//////////////////////////////////////////////////////////////////////


	gulp.task('svgmin', function() {
		gulp.src('assets/img/*.svg')								// Gets all SVGs
		.pipe(svgmin())												// Minifies SVG
		.pipe(gulp.dest('assets/img_min/'));						// Set destination to assets/img_min/
		util.log('SVG images minified');							// Output to terminal
	});

	gulp.task('imagemin', function () {
		gulp.src(['assets/img/*', '!assets/img/*.svg'])				// Gets all images except SVGs
		.pipe(imagemin())											// Minifies
		.pipe(gulp.dest('assets/img_min/'));						// Set destination to assets/img_min/
		util.log('Images minified');								// Output to terminal
	});














//
//		Default gulp task.
//
//////////////////////////////////////////////////////////////////////


gulp.task('watch', function(){

	// Listen on port 35729 for LiveReload
	server.listen(35729, function (err) {if (err) {return console.log(err) };
		gulp.watch("assets/scss/**/*.scss", ['sass']);				// Watch and run sass on changes
		gulp.watch("assets/js/_*.js", ['javascripts']);				// Watch and run javascripts on changes
		gulp.watch("assets/img/*", ['imagemin', 'svgmin']);		// Watch and minify images on changes

		gulp.watch('**/*.php').on('change', function(file) {
			util.log('PHP FILES CHANGED!');
		    server.changed(file.path);
		});

	});
});

gulp.task('default', ['sass', 'javascripts', 'copy', 'svgmin', 'imagemin', 'watch']);


