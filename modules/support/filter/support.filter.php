<?php
/**
 * Fichier de gestion des filtres
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Classe de gestion des filtres
 */
class Support_Filter {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_filter( 'wps_my_account_extra_part_menu', array( $this, 'callback_my_account_menu' ) );
		add_filter( 'wps_my_account_extra_panel_content', array( $this, 'callback_my_account_content' ), 10, 2 );
	}

	/**
	 * [callback_my_account_menu description]
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_my_account_menu() {
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/menu' );
	}

	/**
	 * [callback_my_account_content description]
	 *
	 * @param  [type] $output         [description]
	 * @param  [type] $dashboard_part [description]
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function callback_my_account_content( $output, $dashboard_part ) {
		if ( 'support' === $dashboard_part ) {
			$current_customer_account_to_show = $_COOKIE['wps_current_connected_customer'];

			$tasks_id = array();

			$last_modification_date = '';
			$last_modification_date_mysql = '';

			$tasks = \task_manager\Task_Class::g()->get_tasks( array(
				'post_parent' => $current_customer_account_to_show,
			) );

			$args = array(
				'post_parent' => $current_customer_account_to_show,
				'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
				'numberposts' => -1,
				'post_status' => 'any',
			);

			$children = get_posts( $args );

			if ( ! empty( $children ) ) {
				foreach ( $children as $child ) {
					$tasks = array_merge( $tasks, \task_manager\Task_Class::g()->get_tasks( array(
						'post_parent' => $child->ID,
					) ) );
				}
			}

			if ( ! empty( $tasks ) ) {
				foreach ( $tasks as $task ) {
					$tasks_id[] = $task->id;

					if ( empty( $last_modification_date ) || ( $last_modification_date_mysql < $task->date_modified['date_input']['date'] ) ) {
						$last_modification_date_mysql = $task->date_modified['date_input']['date'];
						$last_modification_date = $task->date_modified['date_input']['fr_FR']['date_time'];
					}
				}
			}

			ob_start();
			\eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/main', array(
				'last_modification_date' => $last_modification_date,
				'tasks' => $tasks,
				'tasks_id' => implode( ',', $tasks_id ),
				'parent_id' => $current_customer_account_to_show,
				'customer_id' => $current_customer_account_to_show,
				'user_id' => get_current_user_id(),
			) );
			$output = ob_get_clean();
		}

		return $output;
	}
}

new Support_Filter();
