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
		add_filter( 'task_manager_notify_send_notification_recipients', array( $this, 'callback_task_manager_notify_send_notification_recipients' ), 10, 3 );
	}


	public function callback_task_manager_popup_notify_after( $content, $task ) {
		if ( 0 === $task->parent_id ) {
			return $content;
		}

		$post_type = get_post_type( $task->parent_id );

		if ( ! $post_type ) {
			return $content;
		}

		if ( ! in_array( $post_type, \eoxia\Config_Util::$init['task-manager-wpshop']->associate_post_type ) ) {
			return false;
		}

		ob_start();
		require( TM_WPS_PATH . '/core/view/notify/main.view.php' );
		$content .= ob_get_clean();
		return $content;
	}

	public function callback_task_manager_notify_send_notification_recipients( $recipients, $task, $form_data ) {
		if ( empty( $form_data['notify_customer'] ) ) {
			return $recipients;
		}

		$post = get_post( $task->parent_id );

		if ( ! $post ) {
			return $recipients;
		}

		$user_info = get_userdata( $post->post_author );
		$recipients[] = $user_info->user_email;

		return $recipients;
	}
}

new Task_Manager_Wpshop_Core_Filter();
