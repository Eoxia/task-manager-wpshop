<?php
/**
 * La classe principale de l'application.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.1.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Task_Manager_Wpshop_Core extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}

	/**
	 * [get_customers_id description]
	 * @return [type] [description]
	 */
	public function get_customers_id() {
		$customers_post_id = get_posts( array(
			'post_type' => 'wpshop_customers',
			'post_status' => 'any',
			'posts_per_page' => \eoxia\Config_util::$init['task-manager']->task->posts_per_page,
			'fields' => 'ids',
		) );

		if ( ! empty( $customers_post_id ) ) {
			$customers_post_id = implode( ',', $customers_post_id );
		}
 }

}

new Task_Manager_Wpshop_Core();
