<?php
/**
 * Gestion des actions cotées 'support'.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
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
 * Gestion des actions cotées 'support'.
 */
class Support_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'wp_ajax_open_popup_create_ticket', array( $this, 'callback_open_popup_create_ticket' ) );
		add_action( 'wp_ajax_create_ticket', array( $this, 'callback_create_ticket' ) );
		add_action( 'wp_ajax_load_last_activity_in_support', array( $this, 'callback_load_last_activity_in_support' ) );
	}

	/**
	 * Appel la vue contenant le formulaire pour faire une nouvelle demande.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function callback_open_popup_create_ticket() {
		check_ajax_referer( 'open_popup_create_ticket' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/form-create-ticket' );
		wp_send_json_success( array(
			'namespace' => 'taskManagerFrontendWPShop',
			'module' => 'frontendSupport',
			'callback_success' => 'openedPopupCreateTicket',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Fonction de callback pour les demandes de tâches depuis le frontend
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function callback_create_ticket() {
		check_ajax_referer( 'create_ticket' );

		$subject = ! empty( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
		$description = ! empty( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';

		if ( empty( $subject ) || empty( $description ) || strlen( $subject ) > 150 ) {
			wp_send_json_error();
		}

		global $wpdb;

		$current_customer_account_to_show = $_COOKIE['wps_current_connected_customer'];

		$edit = false;
		$query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name LIKE %s AND post_parent = %d", array( 'ask-task-%', $current_customer_account_to_show ) );
		$list_task = $wpdb->get_results( $query );
		/** On crée la tâche */
		if ( 0 === count( $list_task ) ) {
			$task = \task_manager\Task_Class::g()->update(
				array(
					'title' => __( 'Ask', 'task-manager' ),
					'slug' => 'ask-task-' . get_current_user_id(),
					'parent_id' => $current_customer_account_to_show,
				)
			);
			$task_id = $task->id;
		} else {
			$edit = true;
			$task_id = $list_task[0]->ID;
		}
		$task = \task_manager\Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$point_data = array(
			'content' => $subject,
			'post_id' => $task_id,
		);

		$point = \task_manager\Point_Class::g()->update( $point_data );

		$task->task_info['order_point_id'][] = (int) $point->id;
		\task_manager\Task_Class::g()->update( $task );

		$comment_data = array(
			'content' => $description,
			'post_id' => $task_id,
			'comment_parent' => $point->id,
			'time_info' => array(
				'elapsed' => 0,
			),
		);

		\task_manager\Task_Comment_Class::g()->update( $comment_data );

		\eoxia\LOG_Util::log( '', 'task-manager-wpshop' );

		ob_start();
		require( PLUGIN_TASK_MANAGER_PATH . '/module/task/view/frontend/task.view.php' );
		$task_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/created-ticket-success' );
		$success_view = ob_get_clean();

		wp_send_json_success( array(
			'task_id' => $task_id,
			'edit' => $edit,
			'namespace' => 'taskManagerFrontendWPShop',
			'module' => 'frontendSupport',
			'callback_success' => 'createdTicket',
			'task_view' => $task_view,
			'success_view' => $success_view,
		) );
	}
}

new Support_Action();
