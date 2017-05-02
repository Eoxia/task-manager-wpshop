window.taskManagerWpshop.frontendSupport = {};

window.taskManagerWpshop.frontendSupport.init = function() {
	window.taskManagerWpshop.frontendSupport.event();
};

window.taskManagerWpshop.frontendSupport.event = function() {
	jQuery( document ).on( 'click', '.wpeo-ask-task', window.taskManagerWpshop.frontendSupport.slideAskTask );
	jQuery( document ).on( 'keyup', '.wps-section-content .task-search', window.taskManagerWpshop.frontendSupport.searchKey );
	jQuery( document ).on( 'click', '.wps-section-content .search-button', window.taskManagerWpshop.frontendSupport.searchIn );

	jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle', window.taskManagerWpshop.frontendSupport.togglePoint );
	jQuery( document ).on( 'click', '.point-content', window.taskManagerWpshop.frontendSupport.setPointActive );
};

window.taskManagerWpshop.frontendSupport.slideAskTask = function( event ) {
	event.preventDefault();
	jQuery( '#wpeo-window-ask-task' ).slideToggle();
};

window.taskManagerWpshop.frontendSupport.searchKey = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.wps-section-content .search-button' ).click();
	}
};

window.taskManagerWpshop.frontendSupport.searchIn = function( event ) {
	var element = jQuery( this );
	if ( 0 == jQuery( this ).closest( '.wps-section-content' ).find( '.task-search' ).val().length ) {
		jQuery( '.grid-item .task' ).show();
	} else {
		jQuery( '.grid-item .task:visible' ).each( function() {
			var synthesis_task = '';
			synthesis_task += jQuery( this ).text();
			jQuery( this ).find( 'input' ).each( function() {
				synthesis_task += jQuery( this ).val() + ' ';
			} );
			synthesis_task = synthesis_task.replace( /\s+\s/g, ' ' ).trim();

			if ( synthesis_task.search( new RegExp( jQuery( element ).closest( '.wps-section-content' ).find( '.task-search' ).val(), 'i' ) ) == -1 ) {
				jQuery( this ).hide();
			}
		} );
	}
};

window.taskManagerWpshop.frontendSupport.togglePoint = function( event ) {
	event.preventDefault();
	jQuery( this ).find( '.wpeo-point-toggle-arrow' ).toggleClass( 'dashicons-plus dashicons-minus' );
	jQuery( this ).closest( '.wpeo-task-point-use-toggle' ).next( '.completed-point' ).toggleClass( 'hidden' );
};

window.taskManagerWpshop.frontendSupport.setPointActive = function( event ) {
	jQuery( '.point-content.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
};

/**
 * Avant de charger les commentaires, change la dashicons.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élément HTML déclenchant l'action.
 * @return void
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.taskManagerWpshop.frontendSupport.beforeLoadComments = function( triggeredElement ) {
	triggeredElement.toggleClass( 'dashicons-arrow-right-alt2 dashicons-arrow-down-alt2' );
	triggeredElement.closest( 'li' ).find( '.comments' ).toggleClass( 'hidden' );
	return true;
};

window.taskManagerWpshop.frontendSupport.loadedFrontComments = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'li' ).find( '.comments' ).html( response.data.view );
};

window.taskManagerWpshop.frontendSupport.addedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comments .comment.edit' ).after( response.data.view );
};

window.taskManagerWpshop.frontendSupport.askedTask = function( triggeredElement, response ) {
	if ( response.data.edit ) {
		jQuery( '.task[data-id="' + response.data.task_id + '"]' ).replaceWith( response.data.template );
	} else {
		jQuery( '.grid-item' ).prepend( response.data.template );
	}
};
