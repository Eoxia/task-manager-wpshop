<?php
/**
 * Classe gérant les filtres principales de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.1.0
 * @version 1.1.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les filtres principales de l'application.
 */
class Task_Manager_Wpshop_Core_Filter {

	/**
	 * Le constructeur
	 *
	 * @since 1.1.0
	 * @version 1.1.0
	 */
	public function __construct() {
		add_filter( 'task_manager_navigation_after', array( $this, 'callback_task_manager_navigation_after' ) );
		add_filter( 'task_manager_load_more_query_args', array( $this, 'callback_task_manager_load_more_query_args' ), 10, 2 );
	}

	/**
	 * Ajout l'onglet 'Task Client' dans le 'dashboard' de 'Task Manager'.
	 *
	 * @since 1.1.0
	 * @version 1.1.0
	 *
	 * @param  string $content Un contenu vide.
	 * @return string          Un contenu avec l'onglet 'Task Client'.
	 */
	public function callback_task_manager_navigation_after( $content ) {
		ob_start();
		require( TM_WPS_PATH . '/core/view/tab.view.php' );
		$content = ob_get_clean();
		return $content;
	}

	public function callback_task_manager_load_more_query_args( $query_args, $tab ) {

		if ( 'customer' === $tab ) {

		}


		return $query_args;
	}

}

new Task_Manager_Wpshop_Core_Filter();
