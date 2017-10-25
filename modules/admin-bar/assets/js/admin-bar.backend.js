window.eoxiaJS.taskManagerBackendWPShop.adminBar = {};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.init = function() {
	window.eoxiaJS.taskManagerBackendWPShop.adminBar.event();
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.event = function() {
	jQuery( document ).on( 'click', '.popup.popup-quick-task .button.blue', window.eoxiaJS.taskManagerBackendWPShop.adminBar.closePopup );

};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.loadedPopupQuickTask = function( triggeredElement, response ) {
	jQuery( '.popup.popup-quick-task .container.loading' ).removeClass( 'loading' );
	jQuery( '.popup.popup-quick-task .container .content' ).html( response.data.view );
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.createdQuickTask = function( triggeredElement, response ) {
	jQuery( '.popup.popup-quick-task .container .content' ).html( response.data.view );
};

window.eoxiaJS.taskManagerBackendWPShop.adminBar.closePopup = function( event ) {
	jQuery( '.popup.popup-quick-task' ).removeClass( 'active' );
};
