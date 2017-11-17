window.eoxiaJS.taskManagerFrontendWPShop = {};
window.eoxiaJS.taskManagerBackendWPShop = {};
window.eoxiaJS.taskManagerBackendWPShop.core = {};


window.eoxiaJS.taskManagerBackendWPShop.core.init = function() {
	window.eoxiaJS.taskManagerBackendWPShop.core.event();
};

window.eoxiaJS.taskManagerBackendWPShop.core.event = function() {
	jQuery( document ).on( 'change', '.popup-notification input[type="checkbox"]', window.eoxiaJS.taskManagerBackendWPShop.core.changeCheckbox );
};

window.eoxiaJS.taskManagerBackendWPShop.core.changeCheckbox = function( event ) {
	jQuery( '.popup-notification .more-info' ).addClass( 'hidden' );

	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '.popup-notification .more-info' ).removeClass( 'hidden' );
	}
};
