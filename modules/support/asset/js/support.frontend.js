window.eoxiaJS.taskManagerWpshop.frontendSupport = {};

window.eoxiaJS.taskManagerWpshop.frontendSupport.init = function() {
	window.eoxiaJS.taskManagerWpshop.frontendSupport.event();
};

window.eoxiaJS.taskManagerWpshop.frontendSupport.event = function() {
	jQuery( document ).on( 'click', '.wpeo-ask-task', window.eoxiaJS.taskManagerWpshop.frontendSupport.slideAskTask );
	jQuery( document ).on( 'keyup', '.wps-section-content .task-search', window.eoxiaJS.taskManagerWpshop.frontendSupport.searchKey );
	jQuery( document ).on( 'click', '.wps-section-content .search-button', window.eoxiaJS.taskManagerWpshop.frontendSupport.searchIn );
};

window.eoxiaJS.taskManagerWpshop.frontendSupport.slideAskTask = function( event ) {
	event.preventDefault();
	jQuery( '#wpeo-window-ask-task' ).slideToggle();
};

window.eoxiaJS.taskManagerWpshop.frontendSupport.searchKey = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.wps-section-content .search-button' ).click();
	}
};

window.eoxiaJS.taskManagerWpshop.frontendSupport.searchIn = function( event ) {
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

window.eoxiaJS.taskManagerWpshop.frontendSupport.askedTask = function( triggeredElement, response ) {
	if ( response.data.edit ) {
		jQuery( '.wpeo-project-task[data-id="' + response.data.task_id + '"]' ).replaceWith( response.data.template );
	} else {
		jQuery( '.list-task' ).prepend( response.data.template );
	}
};
