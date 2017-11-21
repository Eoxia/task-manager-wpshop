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
	frontend_js: ['core/assets/js/init.js', '**/*.frontend.js' ],
	backend_js: ['core/assets/js/init.js', '**/*.backend.js' ],
	global_js: ['core/assets/js/init.global.js', '**/*.global.js' ]
};

gulp.task( 'js_frontend', function() {
	return gulp.src( paths.frontend_js )
		.pipe( concat( 'frontend.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
} );

gulp.task( 'js_backend', function() {
	return gulp.src( paths.backend_js )
		.pipe( concat( 'backend.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
} );

gulp.task( 'global_js', function() {
	return gulp.src( paths.global_js )
		.pipe( concat( 'global.min.js' ) )
		.pipe( gulp.dest( 'core/assets/js/' ) );
} );

gulp.task( 'default', function() {
	gulp.watch( paths.frontend_js, [ 'js_frontend' ] );
	gulp.watch( paths.backend_js, [ 'js_backend' ] );
	gulp.watch( paths.global_js, [ 'global_js' ] );
});
