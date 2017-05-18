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

jQuery( document ).on( 'blur keyup paste keydown click', '.comment .content', window.taskManagerWpshop.frontendSupport.updateHiddenInput );
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
		jQuery( '.list-task .wpeo-project-task' ).show();
	} else {
		jQuery( '.list-task .wpeo-project-task:visible' ).each( function() {
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
	jQuery( this ).closest( '.wpeo-task-point-use-toggle' ).find( '.points.completed' ).toggleClass( 'hidden' );
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

	if ( triggeredElement.hasClass( 'dashicons-arrow-right-alt2' ) ) {
		triggeredElement.closest( 'div.point' ).find( '.comments' ).toggleClass( 'hidden' );
		return false;
	}

	return true;
};

window.taskManagerWpshop.frontendSupport.loadedFrontComments = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.point' ).find( '.comments' ).html( response.data.view );
	triggeredElement.closest( 'div.point' ).find( '.comments' ).toggleClass( 'hidden' );
};

window.taskManagerWpshop.frontendSupport.addedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-comment-container' ).find( 'input[name="content"]' ).val( '' );
	jQuery( triggeredElement ).closest( '.wpeo-comment-container' ).find( '.content' ).html( '' );
	jQuery( triggeredElement ).closest( '.comments .comment.new' ).after( response.data.view );
	jQuery( triggeredElement ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
};

window.taskManagerWpshop.frontendSupport.askedTask = function( triggeredElement, response ) {
	if ( response.data.edit ) {
		jQuery( '.wpeo-project-task[data-id="' + response.data.task_id + '"]' ).replaceWith( response.data.template );
	} else {
		jQuery( '.list-task' ).prepend( response.data.template );
	}
};

/**
 * Met à jour le champ caché contenant le texte du comment écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.taskManagerWpshop.frontendSupport.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.comment' ).find( '.wpeo-comment-content input[name="content"]' ).val( jQuery( this ).html() );
};
