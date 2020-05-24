var gulp = require('gulp');
var sass = require('gulp-sass');
var notify = require('gulp-notify');
var autoprefixer = require('gulp-autoprefixer');

var paths = {
	sass : 'assets/scss/**/*.scss',
};



gulp.task('watch', function() {
	gulp.watch(paths.sass, ['styles']);
});


gulp.task('styles', function() {
  return gulp.src('assets/scss/app.scss')
    .pipe(sass({
        errLogToConsole: false,
    }).on('error', reportError ))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest('./assets/css/'))
    .pipe(notify({ message: 'Styles task complete' }));
});

var reportError = function (error) {
    notify({
        title: 'Pieronski hasiok!',
        message: error.message,
    }).write(error);

    console.log(error.toString());

    this.emit('end');
};

gulp.task('default', ['styles', 'watch']);

