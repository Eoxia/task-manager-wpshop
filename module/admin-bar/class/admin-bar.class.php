<?php
/**
 * Define functions for admin bar support extension for WPShop
 *
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des actions
 */
class Admin_Bar_Class extends \eoxia\Singleton_Util {

	/**
	 * Instanciation du module
	 */
	protected function construct() { }

	public function init_quick_task( $wp_admin_bar ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'backend/button-quick-task' );
		$button_open_popup = array(
			'id' => 'button-open-popup-quick-task',
			'parent' => 'new-content',
			'title' => ob_get_clean(),
		);

		$wp_admin_bar->add_node( $button_open_popup );
	}

	public function init_customer_link( $wp_admin_bar ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );
		$have_new = false;

		if ( ! empty( $ids ) ) {
			$have_new = true;

			$count = count( $ids );
		}

		$link_to_page = array(
			'id' => 'button-open-popup-last-ask-customer',
			'href' => admin_url( 'admin.php?page=task-manager-indicator' ),
			'title' => '<img src="' . PLUGIN_TASK_MANAGER_URL . 'core/asset/icon-16x16.png" alt="TM" />',
		);

		if ( $have_new ) {
			$link_to_page['title'] .= '<span class="wp-core-ui wp-ui-notification">' . $count . '</span>';
		}

		$wp_admin_bar->add_node( $link_to_page );
	}
}

new Admin_Bar_Class();
