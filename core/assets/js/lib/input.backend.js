window.taskManagerWpshop.input = {};

window.taskManagerWpshop.input.init = function() {
	window.taskManagerWpshop.input.event();
};

window.taskManagerWpshop.input.event = function() {
  jQuery( document ).on( 'keyup', '.digirisk-wrap .form-element input, .digirisk-wrap .form-element textarea', window.taskManagerWpshop.input.keyUp );
};

window.taskManagerWpshop.input.keyUp = function( event ) {
	if ( 0 < jQuery( this ).val().length ) {
		jQuery( this ).closest( '.form-element' ).addClass( 'active' );
	} else {
		jQuery( this ).closest( '.form-element' ).removeClass( 'active' );
	}
};
