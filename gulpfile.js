var gulp		= require('gulp'),
		uglify 	= require('gulp-uglify'),
		concat	= require('gulp-concat'),
		less		= require('gulp-less'),
		jshint	= require('gulp-jshint'),
		browserSync = require('browser-sync');

var paths = {
	js: {
		src: ['assets/vendor/bootstrap/js/*.js',
					'assets/js/plugins/*.js',
					'assets/js/_*.js'],
		dest: "assets/js"
	},
	less: {
		src: "assets/less/main.less",
		dest: "assets/css"
	}
}

gulp.task('less', function() {
	return gulp.src(paths.less.src)
		.pipe(less())
		.pipe(concat("main.css"))
		.pipe(gulp.dest(paths.less.dest));
});

gulp.task('js', function() {
	return gulp.src(paths.js.src)
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(concat("scripts.js"))
		.pipe(gulp.dest(paths.js.dest));
});

gulp.task('browser-sync', function() {
	var files = [
		'assets/less/**/*.less',
		'templates/*.php',
		'*.php'
	];

	browserSync.init(files, {
		proxy: "localhost:7888/wordpress/",
		notify: false
	});
});

gulp.task('watch', function() {
	var watcher = gulp.watch('assets/less/**/*.less', ['less']);
	watcher.on('change', function(event) {
		console.log('Event type: ' + event.type);
		console.log('Event path: ' + event.path);
	});
})

gulp.task('default', ['less','js', 'browser-sync'], function() {
	gulp.watch('assets/less/**/*.less', ['less']);
});