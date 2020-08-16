var gulp = require('gulp'),
	sass = require('gulp-sass'),
	rename = require('gulp-rename'),
	uglify = require('gulp-uglify'),
	bower = require('gulp-bower'),
	concat = require('gulp-concat'),
	rubySass = require('gulp-ruby-sass'),
	minifycss = require('gulp-minify-css'),
	notify = require("gulp-notify"),
	mainBowerFiles = require('main-bower-files'),
	autoprefixer = require('gulp-autoprefixer'),
	browserSync = require('browser-sync'),
	reload = browserSync.reload;

var sassOptions = {
	errLogToConsole: true,
	outputStyle: 'expanded',
	sourceComments: 'map'
};




// Compiling Sass
gulp.task('sass', function () {
	return gulp.src('scss/**/*.scss').pipe(sass(sassOptions)).on("error", notify.onError(function (error) {
		return "Error: " + error.message;
	})).pipe(autoprefixer({
		browsers: ['last 2 version', 'safari 5', 'ie 9', 'opera 12.1', 'ios 6', 'android 4']
	})).pipe(rename({
		suffix: '.min'
	})).pipe(minifycss()).pipe(gulp.dest('app/css')).pipe(reload({
		stream: true
	}));
});
gulp.task('html', function () {
	gulp.src('app/**/*.html', ['html'])
		.pipe(reload({
			stream: true
		}));

});



// Javascript Task
gulp.task('scripts', function () {
	gulp.src(['js/**/*.js', '!js/**/*.min.js'])
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(concat('bundle.js'))
		.pipe(uglify())
		.pipe(gulp.dest('app/js'))


});


// Minify CSS

gulp.task('minify-css', function () {
	gulp.src(['app/css/**/*.css'])
		.pipe(minifycss({
			keepBreaks: true
		}))
		.pipe(gulp.dest('app/css'))


});





gulp.task('watch', function () {
	gulp.watch('scss/**/*.scss', ['sass']);
	gulp.watch('*.html', ['html']);
	gulp.watch('js/*.js', ['scripts']);
	// gulp.watch(config.sassPath + '/**/*.scss', ['css']);

});

gulp.task('browser-sync', function () {
	browserSync({
		server: {
			baseDir: "./"
		}
	});

});



// Default
gulp.task('default', ['sass', 'html', 'minify-css', 'scripts', 'browser-sync', 'watch']);
