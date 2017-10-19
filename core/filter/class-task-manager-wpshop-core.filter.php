<?php
/**
 * Gestion des filtres principaux de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Task_Manager_Wpshop_Core_Filter {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 *
	 * @since 0.1.0
	 * @version 1.1.0
	 */
	public function __construct() {
		add_filter( 'task_manager_popup_notify_after', array( $this, 'callback_task_manager_popup_notify_after' ), 10, 2 );
	}


	public function callback_task_manager_popup_notify_after( $content, $task ) {
		ob_start();
		require( TM_WPS_PATH . '/core/view/notify/main.view.php' );
		$content .= ob_get_clean();
		return $content;
	}

}

new Task_Manager_Wpshop_Core_Filter();
