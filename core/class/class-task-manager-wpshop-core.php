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
	 * Fait le rendu de la metabox
	 *
	 * @param  WP_Post $post les données du post.
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.1.0
	 */
	public function callback_render_metabox( $post ) {
		$parent_id = $post->ID;
		$user_id = $post->post_author;

		$tasks = array();
		$tasks_id = array();
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
			// 'author' => $user_id,
			'post_parent' => $parent_id,
			'post_type' => 'wpshop_shop_order',
			'post_status' => 'any',
		);
		if ( 'wpshop_shop_order' === $post->post_type ) {
			$posts_args['include'] = $parent_id;
		}

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

		$tmp_tasks_id = array_map( function( $task_data ) {
			$tmp_tasks_id = array();
			if ( ! empty( $task_data['data'] ) ) {
				foreach ( $task_data['data'] as $element ) {
					$tmp_tasks_id[] = $element->id;
				}
			}

			return $tmp_tasks_id;
		}, $tasks );

		if ( ! empty( $tmp_tasks_id ) ) {
			foreach ( $tmp_tasks_id as $element ) {
				if ( ! empty( $element ) ) {
					foreach ( $element as $id ) {
						$tasks_id[] = $id;
					}
				}
			}
		}

		$tasks_id = implode( ',', $tasks_id );

		require( PLUGIN_TASK_MANAGER_WPSHOP_PATH . '/core/view/main.view.php' );
	}


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
