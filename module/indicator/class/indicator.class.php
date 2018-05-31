<?php
/**
 * La classe gérant Les indications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La classe gérant Les indications.
 */
class Indicator_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Cette méthode récupères les commentaires des clients WPShop qui sont dans le tableau "_tm_wpshop_ask_ids"
	 * et fait l'affichage.
	 *
	 * @return void
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 */
	public function callback_customer_support() {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$datas    = array();
		$comments = array();

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points ) {
				if ( ! empty( $points ) ) {
					foreach ( $points as $point_id => $id ) {
						if ( ! empty( $id ) ) {
							$comments = array_merge( $comments, \task_manager\Task_Comment_Class::g()->get( array(
								'comment__in' => $id,
							) ) );
						}
					}
				}
			}
		}

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->data['point'] = \task_manager\Point_Class::g()->get( array(
					'id' => $comment->data['parent_id'],
				), true );

				$comment->data['task'] = \task_manager\Task_Class::g()->get( array(
					'id' => $comment->data['post_id'],
				), true );

				$comment->data['post_parent'] = null;

				if ( ! empty( $comment->data['task']->data['parent_id'] ) ) {
					$comment->data['post_parent'] = get_post( $comment->data['task']->data['parent_id'] );
				}

				// Organisé par date pour la lecture dans le template.
				$sql_date                      = substr( $comment->data['date']['raw'], 0, strlen( $comment->data['date']['raw'] ) - 9 );
				$time                          = substr( $comment->data['date']['raw'], 11, strlen( $comment->data['date']['raw'] ) );
				$datas[ $sql_date ][ $time ][] = $comment;
			}
		}

		krsort( $datas );

		\eoxia\View_Util::exec( 'task-manager-wpshop', 'indicator', 'backend/request', array(
			'datas' => $datas,
		) );
	}

	/**
	 * Supprimes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.3.0
	 * @version 1.3.0
	 *
	 * @param integer $id L'ID du commentaire.
	 */
	public function remove_entry_customer_ask( $id ) {
		\eoxia\LOG_Util::log( '------------------------------------------------------------------------------------------------', 'task-manager-wpshop' );
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$comment = \task_manager\Task_Comment_Class::g()->get( array(
			'id' => $id,
		), true );

		if ( 0 === $comment->data['id'] ) {
			return false;
		}

		$comment_found_in = array(
			'task_id'  => 0,
			'point_id' => 0,
		);

		\eoxia\LOG_Util::log( sprintf( __( 'Current support request list: %s', 'task-manager-wpshop' ), wp_json_encode( $ids ) ), 'task-manager-wpshop' );
		\eoxia\LOG_Util::log( sprintf( __( 'Comment for removing in request %s', 'task-manager-wpshop' ), wp_json_encode( $comment ) ), 'task-manager-wpshop' );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points_ids ) {
				if ( ! empty( $points_ids ) ) {
					foreach ( $points_ids as $point_id => $comments_ids ) {
						$key = array_search( $comment->data['id'], $comments_ids, true );
						if ( false !== $key ) {
							array_splice( $ids[ $task_id ][ $point_id ], $key, 1 );
						}
					}
				}
			}
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
	}

}

new Indicator_Class();
