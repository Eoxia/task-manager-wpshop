var gulp = require( 'gulp' );
var please = require( 'gulp-pleeease' );
var watch = require( 'gulp-watch' );
var plumber = require( 'gulp-plumber' );
var rename = require( 'gulp-rename' );
var cssnano = require( 'gulp-cssnano' );
var concat = require( 'gulp-concat' );
var glob = require( 'glob' );
var uglify = require( 'gulp-uglify' );
var sass = require( 'gulp-sass' );

var paths = {
	frontend_js: ['core/assets/js/init.js', '**/*.frontend.js' ]
};

gulp.task( 'js_frontend', function() {
	return gulp.src( paths.frontend_js )
		.pipe( concat( 'frontend.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
} );

gulp.task( 'default', function() {
	gulp.watch( paths.frontend_js, [ 'js_frontend' ] );
});
