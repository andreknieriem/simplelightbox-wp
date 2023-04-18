let gulp = require("gulp")
    sass = require('gulp-sass')(require('node-sass'));

// default standalone file
gulp.task('sass', () => {
    return gulp.src('./resources/sass/*.scss')
        .pipe(sass({}))
        .pipe(gulp.dest('./resources/css'));
});

gulp.task('build', gulp.series('sass'));

gulp.task('watch', () => {
    gulp.watch('./resources/sass/*.scss', gulp.series('sass'));
});
