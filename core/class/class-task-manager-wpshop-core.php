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
class Task_Manager_Wpshop_Core extends \eoxia\Singleton_Util {

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

		$tasks = array();
		$total_time_elapsed = 0;

		if ( 'wpshop_customers' === $post->post_type ) {
			$tasks[ $post->post_parent ]['title'] = __( 'Task in client', 'task-manager-wpshop' );
			$tasks[ $post->post_parent ]['data'] = \task_manager\Task_Class::g()->get_tasks( array(
				'post_parent' => $post->ID,
			) );

			if ( ! empty( $tasks[ $post->post_parent ]['data'] ) ) {
				foreach ( $tasks[ $post->post_parent ]['data'] as $task ) {
					if ( empty( $tasks[ $post->post_parent ]['total_time_elapsed'] ) ) {
						$tasks[ $post->post_parent ]['total_time_elapsed'] = 0;
					}

					$tasks[ $post->post_parent ]['total_time_elapsed'] += $task->time_info['elapsed'];
					$total_time_elapsed += $task->time_info['elapsed'];
				}
			}
		}

		$posts_args = array(
			'author' => $user_id,
			'post_type' => 'wpshop_shop_order',
		);
		$posts = get_posts( $posts_args );
		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$meta = get_post_meta( $post->ID, '_order_postmeta', true );
				if ( ! empty( $meta ) ) {
					$tasks[ $post->ID ]['title'] = __( 'Task in order : ', 'task-manager-wpshop' );
					$tasks[ $post->ID ]['title'] .= $meta['order_key'];
					$tasks[ $post->ID ]['data'] = \task_manager\Task_Class::g()->get_tasks( array(
						'post_parent' => $post->ID,
					) );

					if ( empty( $tasks[ $post->ID ]['data'] ) ) {
						unset( $tasks[ $post->ID ] );
					}

					if ( ! empty( $tasks[ $post->ID ]['data'] ) ) {
						foreach ( $tasks[ $post->ID ]['data'] as $task ) {
							if ( empty( $tasks[ $post->ID ]['total_time_elapsed'] ) ) {
								$tasks[ $post->ID ]['total_time_elapsed'] = 0;
							}
							$tasks[ $post->ID ]['total_time_elapsed'] += $task->time_info['elapsed'];
							$total_time_elapsed += $task->time_info['elapsed'];
						}
					}
				}
			}
		}

		$format = '%hh %imin';

		$dtf = new \DateTime( '@0' );
		$dtt = new \DateTime( '@' . ( $total_time_elapsed * 60 ) );

		if ( 1440 <= $total_time_elapsed ) {
			$format = '%aj %hh %imin';
		}

		$total_time_elapsed = $dtf->diff( $dtt )->format( $format );

		require( PLUGIN_TASK_MANAGER_WPSHOP_PATH . '/core/view/main.view.php' );
	}
}

new Task_Manager_Wpshop_Core();
