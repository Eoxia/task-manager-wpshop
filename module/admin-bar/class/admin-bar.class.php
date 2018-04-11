<?php
/**
 * Classe relatives à l'admin bar.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe relatives à l'admin bar.
 */
class Admin_Bar_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	protected function construct() { }

	/**
	 * Ajoutes le logo de TaskManager et le nombre de demande faites par les clients.
	 * En cliquant dessus, renvoies vers la page "task-manager-indicator".
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 * @return void
	 */
	public function init_customer_link( $wp_admin_bar ) {
		$have_new = false;

		$count = Support_Class::g()->get_number_ask();

		if ( 0 < $count ) {
			$have_new = true;
		}

		$link_to_page = array(
			'id'    => 'button-open-popup-last-ask-customer',
			'href'  => admin_url( 'admin.php?page=task-manager-indicator' ),
			'title' => '<img src="' . PLUGIN_TASK_MANAGER_URL . 'core/assets/icon-16x16.png" alt="TM" />',
		);

		if ( $have_new ) {
			$link_to_page['title'] .= '<span class="wp-core-ui wp-ui-notification"><span>' . $count . '</span></span>';
		}

		$wp_admin_bar->add_node( $link_to_page );
	}
}

new Admin_Bar_Class();
