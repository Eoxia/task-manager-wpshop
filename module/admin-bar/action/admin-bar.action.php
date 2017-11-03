<?php
/**
 * Gestion des actions relatives à l'admin bar.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des actions
 */
class Admin_Bar_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'wp_ajax_load_popup_quick_task', array( $this, 'callback_load_popup_quick_task' ) );
		add_action( 'wp_ajax_create_quick_task', array( $this, 'callback_create_quick_task' ) );
	}

	/**
	 * Permet d'afficher la dashicons qui vas être affiché dans la barre de WordPress.
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 *
	 * @return void
	 *
	 * @since 1.0.1
	 * @version 1.2.0
	 *
	 * @todo: Revoir cette méthode
	 */
	public function callback_admin_bar_menu( $wp_admin_bar ) {
		if ( current_user_can( 'administrator' ) ) {
			Admin_Bar_Class::g()->init_quick_task( $wp_admin_bar );
			Admin_Bar_Class::g()->init_customer_link( $wp_admin_bar );
		}
	}

	/**
	 * Cette méthode appelle une vue qui sera renvoyé au rendu de la thickbox.
	 * Elle charge également les 5 derniers commentaires des clients de WPShop.
	 *
	 * @return void
	 *
	 * @since 1.0.1.0
	 * @version 1.0.1.0
	 */
	public function callback_open_popup_last_wpshop_customer_comment() {

	}

	public function callback_load_popup_quick_task() {
		check_ajax_referer( 'load_popup_quick_task' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'backend/form-quick-task' );
		wp_send_json_success( array(
			'namespace' => 'taskManagerBackendWPShop',
			'module' => 'adminBar',
			'callback_success' => 'loadedPopupQuickTask',
			'view' => ob_get_clean(),
		) );
	}

	public function callback_create_quick_task() {
		check_ajax_referer( 'create_quick_task' );

		$content = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '';
		$parent_id = \eoxia\Config_Util::$init['task-manager-wpshop']->id_quick_task;

		if ( 0 === $parent_id || '' === $content || '' === $time ) {
			wp_send_json_error();
		}
		$current_user = wp_get_current_user();
		$task = \task_manager\Task_Class::g()->get( array(
			'post_parent' => $parent_id,
			'name' => 'unclassified',
		), true );
		if ( empty( $task ) ) {
			$task = \task_manager\Task_Class::g()->update( array(
				'parent_id' => $parent_id,
				'title' => __( 'Unclassified', 'task-manager-wpshop' ),
			) );
		}
		$point = \task_manager\Point_Class::g()->get( array(
			'user_id' => $current_user->ID,
			'post__in' => $task->id,
			'status' => -34070,
			'parent' => 0,
		), true );
		if ( 0 === $point->id ) {
			$point = \task_manager\Point_Class::g()->update( array(
				'status' => '-34070',
				'author_id' => $current_user->ID,
				'post_id' => $task->id,
				'content' => $current_user->user_login,
			) );
			$task->task_info['order_point_id'][] = (int) $point->id;
			\task_manager\Task_Class::g()->update( $task );
		}
		$time = \task_manager\Task_Comment_Class::g()->update( array(
			'status' => '-34070',
			'content' => $content,
			'post_id' => $task->id,
			'parent_id' => $point->id,
			'author_id' => $current_user->ID,
			'time_info' => array(
				'elapsed' => $time,
			),
		) );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'backend/created-quick-task-success' );
		wp_send_json_success( array(
			'namespace' => 'taskManagerBackendWPShop',
			'module' => 'adminBar',
			'callback_success' => 'createdQuickTask',
			'view' => ob_get_clean(),
		) );
	}

}

new Admin_Bar_Action();
