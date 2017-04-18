<?php
/**
 * La classe principale de l'application.
 *
 * @package Eoxia\Plugin
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Task_Manager_Wpshop_Core extends Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}

	/**
	 * Fait le rendu de la metabox
	 *
	 * @param  WP_Post $post les données du post.
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function callback_render_metabox( $post ) {
		$parent_id = $post->ID;
		$user_id = $post->post_author;

		global $wpdb;

		$posts_id = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_parent=" . $user_id );

		$posts = get_posts( array(
			'include' => $posts_id,
			'post_type' => 'wpshop_shop_order',
		) );

		$titles = array();
		$tasks = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$meta = get_post_meta( $post->ID, '_order_postmeta', true );

				$tasks[ $post->post_parent ]['title'] = __( 'Task in order : ', 'task-manager-wpshop' );
				$tasks[ $post->post_parent ]['title'] .= $meta->order_key;
				$tasks[ $post->post_parent ]['id'] = $post->ID;
			}
		}

		require( PLUGIN_TASK_MANAGER_WPSHOP_PATH . '/core/view/main.view.php' );
	}
}

new Task_Manager_Wpshop_Core();
