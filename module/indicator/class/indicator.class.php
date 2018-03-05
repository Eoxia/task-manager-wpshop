<?php
/**
 * La classe gérant Les indications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
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


}

new Indicator_Class();
