var path = require('path');
var gulp = require('gulp');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');

var path = {
	scripts: [
		'public/scripts/jquery.min.js',
		'public/scripts/bootstrap.min.js',
		'public/scripts/jquery.flot.min.js',
		'public/scripts/jquery.flot.canvas.min.js',
		'public/scripts/jquery.flot.time.min.js',
		'public/scripts/jquery.flot.pie.min.js',
		'public/scripts/follower.xcharts.js',
		//'public/scripts/**/*.js',
		'public/scripts/datepicker.js',
		'public/scripts/scripts.js'
	],
	styles: ['public/styles/**/*.css'],
	compiled: 'public/compiled'
};

gulp.task('scripts', function() {

	// Minify all JavaScript code

	return gulp.src(path.scripts)
		.pipe(sourcemaps.init())
		.pipe(concat('scripts.js'))
		.pipe(uglify())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(path.compiled));
});

gulp.task('styles', function() {

	// Compile Stylus styles to CSS

	return gulp.src(path.styles)
		.pipe(concat('style.css'))
		.pipe(autoprefixer('last 3 versions'))
		.pipe(gulp.dest(path.compiled));
});

gulp.task('build', ['styles', 'scripts']);
gulp.task('default', ['build']);
