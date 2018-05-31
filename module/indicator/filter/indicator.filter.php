<?php
/**
 * Les filtres relatives aux indications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux indications.
 */
class Indicator_Filter {

	/**
	 * Initialise les filtres liées au indications.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_filter( 'tm_indicator_menu_title', array( $this, 'callback_tm_indicator_menu_title' ) );
	}

	/**
	 * Renvoies le titre du menu "Indicator" modifié avec le nombre de demande des clients.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  string $title Le titre du menu.
	 * @return string        Le titre du menu modifié.
	 */
	public function callback_tm_indicator_menu_title( $title ) {
		$number_ask = Support_Class::g()->get_number_ask();

		if ( $number_ask > 0 ) {
			// $title .= '<span class="wp-core-ui wp-ui-notification"><span>' . $number_ask . '</span></span>';
		}
		return $title;
	}

}

new Indicator_Filter();
