var path = require('path');
var gulp = require('gulp');
var concat = require('gulp-concat');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');

var paths = {
	scripts: [
		'public/scripts/jquery.min.js',
		'public/scripts/bootstrap.min.js',
		'public/scripts/jquery.flot.min.js',
		'public/scripts/jquery.flot.canvas.min.js',
		'public/scripts/jquery.flot.time.min.js',
		'public/scripts/jquery.flot.pie.min.js',
		'public/scripts/follower.xcharts.js',
		'public/scripts/datepicker.js',
		'public/scripts/scripts.js'
	],
	core: [
		'vendor/follower/core/frontend/vf.js',
		'vendor/follower/core/frontend/**/*.js'
	],
	mycode: [
		'public/test/js/index.js',
		'public/test/js/**/*.js'
	],
	styles: ['public/styles/**/*.css'],
	compiled: 'public/compiled'
};

gulp.task('scripts', function() {

	// Minify all JavaScript code

	return gulp.src(paths.scripts)
		.pipe(concat('scripts.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.compiled));
});

gulp.task('test', function() {

	// Minify all JavaScript code

	return gulp.src(paths.core.concat(paths.mycode))
		.pipe(concat('test.js'))
		.pipe(gulp.dest(paths.compiled));
});


gulp.task('styles', function() {

	// Compile Stylus styles to CSS

	return gulp.src(paths.styles)
		.pipe(concat('style.css'))
		.pipe(autoprefixer('last 3 versions'))
		.pipe(gulp.dest(paths.compiled));
});

gulp.task('build', ['styles', 'scripts']);
gulp.task('default', ['build']);
