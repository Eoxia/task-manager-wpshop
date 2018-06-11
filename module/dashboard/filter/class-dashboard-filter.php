<?php
/**
 * Les filtres relatives au dasboard de task-manager quand WPShop est activé..
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux indications.
 */
class Dashboard_Filter {

	/**
	 * Initialise les filtres liées au indications.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_filter( 'tm_search_options_bottom', array( $this, 'callback_tm_search_options_bottom' ) );
	}

	/**
	 * [callback_tm_search_options_bottom description]
	 *
	 * @param  string $content Le contenu actuel correspondant au filtre.
	 *
	 * @return string          Le "nouveau" contenu qui a été modifié par le filtre.
	 */
	public function callback_tm_search_options_bottom( $content ) {

		return $content;
	}

}

new Dashboard_Filter();
