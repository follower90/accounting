
var path = require('path'),
	gulp = require('gulp'),
	concat = require('gulp-concat'),
	autoprefixer = require('gulp-autoprefixer'),
	uglify = require('gulp-uglify'),
	stylus = require('gulp-stylus'),
	ks = require('kouto-swiss'),
	minify = require('gulp-minify-css');

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
		'vendor/follower/core/frontend/app.js',
		'vendor/follower/core/frontend/modules/**/*.js',
		'vendor/follower/core/frontend/**/*.js'
	],
	mycode: [
		'public/test/js/index.js',
		'public/test/js/**/*.js'
	],
	cssframework: {
		src: [
			'vendor/follower/core/stylus/reset.styl',
			'vendor/follower/core/stylus/**/*.styl'
		],
		dest: 'public/test/css'
	},
	styles: ['public/styles/**/*.css'],
	compiled: 'public/compiled'
};

gulp.task('scripts', function () {

	// Minify all JavaScript code

	return gulp.src(paths.scripts)
		.pipe(concat('scripts.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.compiled));
});

gulp.task('test', function () {

	// Minify all JavaScript code

	return gulp.src(paths.core.concat(paths.mycode))
		.pipe(concat('test.js'))
		.pipe(gulp.dest(paths.compiled));
});

gulp.task('cssframework', function () {

	// Build css framework Stylus files
	console.log(paths.cssframework.dest);
	return gulp.src(paths.cssframework.src)
		.pipe(stylus({
			compress: false,
			use: [ks()],
			linenos: false
		}))
		.pipe(concat('core.css'))
		//.pipe(minify({compatibility: 'ie8'}))
		.pipe(autoprefixer('last 2 versions'))
		.pipe(gulp.dest(paths.cssframework.dest));
});

gulp.task('styles', function () {

	// Compile styles

	return gulp.src(paths.styles)
		.pipe(concat('style.css'))
		.pipe(autoprefixer('last 3 versions'))
		.pipe(gulp.dest(paths.compiled));
});

gulp.task('build', ['styles', 'scripts']);
gulp.task('default', ['build']);
