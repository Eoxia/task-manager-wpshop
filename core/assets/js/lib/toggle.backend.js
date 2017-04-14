window.taskManagerWpshop.toggle = {};

window.taskManagerWpshop.toggle.init = function() {
	window.taskManagerWpshop.toggle.event();
};

window.taskManagerWpshop.toggle.event = function() {
  jQuery( document ).on( 'click', '.toggle:not(.disabled), .toggle:not(.disabled) i', window.taskManagerWpshop.toggle.open );
  jQuery( document ).on( 'click', 'body', window.taskManagerWpshop.toggle.close );
};

window.taskManagerWpshop.toggle.open = function( event ) {
	var target = undefined;
	var elementToggle = jQuery( this );

	if ( elementToggle.is( 'i' ) ) {
		elementToggle = elementToggle.parents( '.toggle' );
	}

	jQuery( '.toggle .content.active' ).removeClass( 'active' );

	if ( elementToggle.data( 'parent' ) ) {
		target = elementToggle.closest( '.' + elementToggle.data( 'parent' ) ).find( '.' + elementToggle.data( 'target' ) );
	} else {
		target = jQuery( '.' + elementToggle.data( 'target' ) );
	}

	if ( target ) {
	  target.toggleClass( 'active' );
	  event.stopPropagation();
	}
};

window.taskManagerWpshop.toggle.close = function( event ) {
	jQuery( '.toggle .content' ).removeClass( 'active' );
	event.stopPropagation();
};
