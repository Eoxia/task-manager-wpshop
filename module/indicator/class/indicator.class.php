<?php
/**
 * La classe gérant Les indications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.2.0
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
	 * @version 1.2.0
	 */
	public function callback_customer_support() {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$comments = array();

		if ( ! empty( $ids ) ) {
			$comments = \task_manager\Task_Comment_Class::g()->get( array(
				'comment__in' => $ids,
				'status' => -34070,
			) );
		}

		if ( ! empty( $comments ) ) {
			foreach ( $comments as &$comment ) {
				$comment->point = \task_manager\Point_Class::g()->get( array(
					'id' => $comment->parent_id,
				), true );

				$comment->task = \task_manager\Task_Class::g()->get( array(
					'id' => $comment->post_id,
				), true );

				$comment->post_parent = get_post( $comment->task->parent_id );
			}
		}

		\eoxia\View_Util::exec( 'task-manager-wpshop', 'indicator', 'backend/request', array(
			'comments' => $comments,
		) );
	}


}

new Indicator_Class();
