window.eoxiaJS.taskManagerBackendWPShop.adminBar = {};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.init = function() {
	window.eoxiaJS.taskManagerBackendWPShop.adminBar.event();
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.event = function() {
	jQuery( document ).on( 'click', '.popup.popup-quick-task .button.close', window.eoxiaJS.taskManagerBackendWPShop.adminBar.closePopup );
	jQuery( document ).on( 'keyup', '.popup.popup-quick-task textarea, .popup.popup-quick-task input[name="time"]', window.eoxiaJS.taskManagerBackendWPShop.adminBar.triggerCreate );

};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.loadedPopupQuickTask = function( triggeredElement, response ) {
	jQuery( '.popup.popup-quick-task .container.loading' ).removeClass( 'loading' );
	jQuery( '.popup.popup-quick-task .container .content' ).html( response.data.view );

	jQuery( '.popup.popup-quick-task .container .content textarea' ).focus();
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.createdQuickTask = function( triggeredElement, response ) {
	jQuery( '.popup.popup-quick-task .container .content' ).html( response.data.view );
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.closePopup = function( event ) {
	jQuery( '.popup.popup-quick-task' ).removeClass( 'active' );
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.popup.popup-quick-task' ).find( '.action-input' ).click();
	}
};
