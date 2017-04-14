window.taskManagerWpshop.form = {};

window.taskManagerWpshop.form.init = function() {
    window.taskManagerWpshop.form.event();
};
window.taskManagerWpshop.form.event = function() {
    jQuery( document ).on( 'click', '.submit-form', window.taskManagerWpshop.form.submitForm );
};

window.taskManagerWpshop.form.submitForm = function( event ) {
	var element = jQuery( this );

	element.closest( 'form' ).addClass( 'loading' );

	event.preventDefault();
	element.closest( 'form' ).ajaxSubmit( {
		success: function( response ) {
			element.closest( 'form' ).removeClass( 'loading' );

			if ( response && response.success ) {
				if ( response.data.module && response.data.callback_success ) {
					window.taskManagerWpshop[response.data.module][response.data.callback_success]( element, response );
				}
			} else {
				if ( response.data.module && response.data.callback_error ) {
					window.taskManagerWpshop[response.data.module][response.data.callback_error]( element, response );
				}
			}
		}
	} );
};
