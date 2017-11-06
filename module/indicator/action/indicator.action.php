<?php
/**
 * Les actions relatives aux indications.
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
 * Les actions relatives aux indications.
 */
class Indicator_Action {

	/**
	 * Initialise les actions liées au indications.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_meta_box( 'tm-indicator-support', __( 'Customer support', 'task-manager' ), array( Indicator_Class::g(), 'callback_customer_support' ), 'task-manager-indicator-support', 'normal' );

		add_action( 'tm_delete_task', array( $this, 'callback_tm_delete_task' ) );
		add_action( 'tm_archive_task', array( $this, 'callback_tm_archive_task' ) );
		add_action( 'tm_complete_point', array( $this, 'callback_tm_complete_point' ) );
		add_action( 'tm_delete_point', array( $this, 'callback_tm_delete_point' ) );
		add_action( 'tm_edit_comment', array( $this, 'callback_tm_edit_comment' ), 3, 10 );

		add_action( 'tm_customer_add_entry_customer_ask', array( $this, 'callback_tm_add_entry_customer_ask' ) );
		add_action( 'tm_customer_remove_entry_customer_ask', array( $this, 'callback_tm_remove_entry_customer_ask' ) );
	}

	public function callback_tm_delete_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $task->id ] ) ) {
			foreach ( $ids[ $task->id ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	public function callback_tm_archive_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $task->id ] ) ) {
			foreach ( $ids[ $task->id ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	public function callback_tm_complete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $point->post_id ] ) ) {
			foreach ( $ids[ $point->post_id ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	public function callback_tm_delete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $point->post_id ] ) ) {
			foreach ( $ids[ $point->post_id ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	public function callback_tm_edit_comment( $task, $point, $comment ) {
		$user = get_userdata( $comment->author_id );

		if ( ! in_array( 'administrator', $user->roles, true ) ) {
			return false;
		}

		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( empty( $ids[ $task->id ] ) ) {
			return false;
		}

		if ( empty( $ids[ $task->id ][ $point->id ] ) ) {
			return false;
		}

		$comments_customer = \task_manager\Task_Comment_Class::g()->get( array(
			'comment__in' => $ids[ $task->id ][ $point->id ],
			'status' => '-34070',
		) );

		if ( ! empty( $comments_customer ) ) {
			foreach ( $comments_customer as $comment_customer ) {
				if ( strtotime( $comment_customer->date['date_input']['date'] ) < strtotime( $comment->date['date_input']['date'] ) ) {
					$this->callback_tm_remove_entry_customer_ask( $comment_customer->id );
				}
			}
		}
	}

	/**
	 * Ajoutes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $id   L'ID du commentaire.
	 *
	 * @return bool
	 */
	public function callback_tm_add_entry_customer_ask( $id ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$comment = \task_manager\Task_Comment_Class::g()->get( array(
			'id' => $id,
		), true );

		if ( 0 === $comment->id ) {
			return false;
		}

		if ( empty( $ids[ $comment->post_id ] ) ) {
			$ids[ $comment->post_id ] = array(
				$comment->parent_id => array(
					$comment->id,
				),
			);
		}

		if ( empty( $ids[ $comment->post_id ][ $comment->parent_id ] ) ) {
			$ids[ $comment->post_id ] = array(
				$comment->parent_id => array(
					$comment->id,
				),
			);
		}

		if ( ! empty( $ids[ $comment->post_id ][ $comment->parent_id ] ) && ! in_array( $comment->id, $ids[ $comment->post_id ][ $comment->parent_id ], true ) ) {
			$ids[ $comment->post_id ][ $comment->parent_id ][] = (int) $comment->id;
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
		return true;
	}

	/**
	 * Supprimes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $id   L'ID du commentaire.
	 *
	 * @return bool
	 */
	public function callback_tm_remove_entry_customer_ask( $id ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$comment = \task_manager\Task_Comment_Class::g()->get( array(
			'id' => $id,
		), true );

		if ( 0 === $comment->id ) {
			return false;
		}

		if ( ! empty( $ids[ $comment->post_id ] ) && ! empty( $ids[ $comment->post_id ][ $comment->parent_id ] ) ) {
			$key = array_search( $comment->id, $ids[ $comment->post_id ][ $comment->parent_id ], true );
			if ( false !== $key ) {
				array_splice( $ids[ $comment->post_id ][ $comment->parent_id ], $key, 1 );
			}
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
	}
}

new Indicator_Action();
