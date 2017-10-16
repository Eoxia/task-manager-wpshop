<?php
/**
 * Fichier de gestion des actions
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Classe de gestion des actions
 */
class Support_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'wp_ajax_ask_task', array( $this, 'callback_ask_task' ) );
		add_action( 'wp_ajax_nopriv_ask_task', array( $this, 'callback_ask_task' ) );
	}

	/**
	 * Fonction de callback pour les demandes de tâches depuis le frontend
	 */
	public function callback_ask_task() {
		check_ajax_referer( 'ask_task' );
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

		$_POST['point']['author_id'] = get_current_user_id();
		$_POST['point']['status'] = '-34070';
		$_POST['point']['date'] = current_time( 'mysql' );
		$_POST['point']['post_id'] = $task_id;

		$point = \task_manager\Point_Class::g()->update( $_POST['point'] );

		$task->task_info['order_point_id'][] = (int) $point->id;
		\task_manager\Task_Class::g()->update( $task );

		ob_start();
		require( PLUGIN_TASK_MANAGER_PATH . '/module/task/view/frontend/task.view.php' );
		wp_send_json_success( array(
			'task_id' => $task_id,
			'edit' => $edit,
			'namespace' => 'taskManagerFrontendWPShop',
			'module' => 'frontendSupport',
			'callback_success' => 'askedTask',
			'template' => ob_get_clean(),
		) );
	}

}

new Support_Action();
