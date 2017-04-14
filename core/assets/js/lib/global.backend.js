window.taskManagerWpshop.global = {};

window.taskManagerWpshop.global.init = function() {};

window.taskManagerWpshop.global.downloadFile = function( urlToFile, filename ) {
	var url = jQuery( '<a href="' + urlToFile + '" download="' + filename + '"></a>' );
	jQuery( '.wrap' ).append( url );
	url[0].click();
	url.remove();
};

window.taskManagerWpshop.global.removeDiacritics = function( input ) {
	var output = '';
	var normalized = input.normalize( 'NFD' );
	var i = 0;
	var j = 0;

	while ( i < input.length ) {
		output += normalized[j];

		j += ( input[i] == normalized[j] ) ? 1 : 2;
		i++;
	}

	return output;
};
