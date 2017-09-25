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
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_my_account_content( $output, $dashboard_part ) {
		if ( 'support' === $dashboard_part ) {

			$id = get_posts( array(
				'posts_per_page' => 1,
				'author' => get_current_user_id(),
				'post_status' => 'any',
				'post_type' => 'wpshop_customers',
				'fields' => 'ids',
			) );

			ob_start();
			\eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/main', array(
				'parent_id' => $id[0],
			) );
			$output = ob_get_clean();
		}

		return $output;
	}
}

new Support_Filter();
