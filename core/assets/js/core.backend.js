/**
 * Initialise l'objet core dans taskManagerWPShop.
 *
 * @since 1.1.0
 * @version 1.1.0
 */
window.eoxiaJS.taskManagerWPShop.core = {};

window.eoxiaJS.taskManagerWPShop.core.init = function() {
};


/**
 * Le callback en cas de réussite à la requête Ajax "load_wpshop_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.1.0
 * @version 1.1.0
 */
window.eoxiaJS.taskManagerWPShop.core.loadedWPShopTask = function( triggeredElement, response ) {
	jQuery( '.wpeo-project-wrap .load-more' ).remove();

	jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	jQuery( '.list-task' ).replaceWith( response.data.view );
	jQuery( '.list-task' ).masonry();
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
};
