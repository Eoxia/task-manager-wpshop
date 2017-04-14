window.taskManagerWpshop.tab = {};

window.taskManagerWpshop.tab.init = function() {
	window.taskManagerWpshop.tab.event();
};

window.taskManagerWpshop.tab.event = function() {
  jQuery( document ).on( 'click', '.tab-element', window.taskManagerWpshop.tab.load );
};

window.taskManagerWpshop.tab.load = function( event ) {
	var tabTriggered = jQuery( this );
	var data = {};

  event.preventDefault();
	event.stopPropagation();

	tabTriggered.closest( '.content' ).removeClass( 'active' );

	if ( ! tabTriggered.hasClass( 'no-tab' ) && tabTriggered.data( 'action' ) ) {
		jQuery( '.tab .tab-element.active' ).removeClass( 'active' );
		tabTriggered.addClass( 'active' );

		data = {
			action: 'load_tab_content',
			_wpnonce: tabTriggered.data( 'nonce' ),
			tab_to_display: tabTriggered.data( 'action' ),
			title: tabTriggered.data( 'title' ),
			element_id: tabTriggered.data( 'id' )
	  };

		jQuery( '.' + tabTriggered.data( 'target' ) ).addClass( 'loading' );

		jQuery.post( window.ajaxurl, data, function( response ) {
			jQuery( '.' + tabTriggered.data( 'target' ) ).replaceWith( response.data.template );

			window.taskManagerWpshop.tab.callTabChanged();
		} );

	}

};

window.taskManagerWpshop.tab.callTabChanged = function() {
	var key = undefined;
	for ( key in window.taskManagerWpshop ) {
		if ( window.taskManagerWpshop[key].tabChanged ) {
			window.taskManagerWpshop[key].tabChanged();
		}
	}
};
