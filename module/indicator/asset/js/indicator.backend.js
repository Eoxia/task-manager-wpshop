/**
 * Initialise l'objet "indicator" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.2.0
 * @version 1.3.0
 */
window.eoxiaJS.taskManagerBackendWPShop.indicator = {};
window.eoxiaJS.taskManagerBackendWPShop.indicator.init = function() {};

/**
 * Le callback en cas de réussite à la requête Ajax "load_indicator_activity".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.2.0
 * @version 1.2.0
 */
window.eoxiaJS.taskManagerBackendWPShop.indicator.loadIndicatorActivitySuccess = function( triggeredElement, response ) {
	jQuery( '#tm-indicator-support .inside' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_indicator_support".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.2.0
 * @version 1.2.0
 */
window.eoxiaJS.taskManagerBackendWPShop.indicator.loadIndicatorSupportSuccess = function( triggeredElement, response ) {
	jQuery( '#tm-indicator-support .inside' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "mark_as_read".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.2.0
 * @version 1.2.0
 */
window.eoxiaJS.taskManagerBackendWPShop.indicator.markedAsReadSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.activity' ).fadeOut();
};
