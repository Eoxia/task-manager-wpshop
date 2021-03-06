<?php
/**
 * Les actions relatives aux indications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2018 Eoxia
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
	 * @version 1.3.0
	 */
	public function __construct() {
		add_meta_box( 'tm-indicator-support', __( 'Customer support', 'task-manager-wpshop' ), array( Indicator_Class::g(), 'callback_customer_support' ), 'task-manager-indicator', 'normal' );

		add_action( 'wp_ajax_mark_as_read', array( $this, 'callback_mark_as_read' ) );

		add_action( 'tm_delete_task', array( $this, 'callback_tm_delete_task' ) );
		add_action( 'tm_archive_task', array( $this, 'callback_tm_archive_task' ) );
		add_action( 'tm_complete_point', array( $this, 'callback_tm_complete_point' ) );
		add_action( 'tm_delete_point', array( $this, 'callback_tm_delete_point' ) );
		add_action( 'tm_edit_comment', array( $this, 'callback_tm_edit_comment' ), 10, 3 );

		add_action( 'tm_action_after_comment_update', array( $this, 'callback_tm_add_entry_customer_ask' ) );
		add_action( 'tm_customer_remove_entry_customer_ask', array( $this, 'callback_tm_remove_entry_customer_ask' ) );
		add_action( 'tm_after_move_point_to', array( $this, 'callback_tm_after_move_point_to' ), 10, 2 );
	}

	/**
	 * Lors de la suppresion d'une tâche, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et dans cette tâche.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  Task_Model $task La tâche.
	 * @return void
	 */
	public function callback_tm_delete_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $task->data['id'] ] ) ) {
			foreach ( $ids[ $task->data['id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on archive une tâche, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et dans cette tâche.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  Task_Model $task La tâche.
	 * @return void
	 */
	public function callback_tm_archive_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $task->data['id'] ] ) ) {
			foreach ( $ids[ $task->data['id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on complète un point, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et correspondant à ce point.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  Point_Model $point Le point.
	 * @return void
	 */
	public function callback_tm_complete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $point->data['post_id'] ] ) ) {
			foreach ( $ids[ $point->data['post_id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on supprime un point, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et correspondant à ce point.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  Point_Model $point Le point.
	 * @return void
	 */
	public function callback_tm_delete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( ! empty( $ids[ $point->data['post_id'] ] ) ) {
			foreach ( $ids[ $point->data['post_id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on écrit un commentaire, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et contenu dans le point de ce commentaire.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  Task_Model    $task La tâche.
	 * @param  Point_Model   $point Le point.
	 * @param  Comment_Model $comment Le commentaire.
	 *
	 * @return boolean
	 */
	public function callback_tm_edit_comment( $task, $point, $comment ) {
		$user = get_userdata( $comment->data['author_id'] );

		if ( ! in_array( 'administrator', $user->roles, true ) ) {
			return false;
		}

		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		if ( empty( $ids[ $task->data['id'] ] ) ) {
			return false;
		}

		if ( empty( $ids[ $task->data['id'] ][ $point->data['id'] ] ) ) {
			return false;
		}

		$comments_customer = \task_manager\Task_Comment_Class::g()->get( array(
			'comment__in' => $ids[ $task->data['id'] ][ $point->data['id'] ],
		) );

		if ( ! empty( $comments_customer ) ) {
			foreach ( $comments_customer as $comment_customer ) {
				if ( strtotime( $comment_customer->data['date']['raw'] ) < strtotime( $comment->data['date']['raw'] ) ) {
					$this->callback_tm_remove_entry_customer_ask( $comment_customer->data['id'] );
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
		\eoxia\LOG_Util::log( '------------------------------------------------------------------------------------------------', 'task-manager-wpshop' );
		$comment = \task_manager\Task_Comment_Class::g()->get( array(
			'id' => $id,
		), true );

		if ( 0 === $comment->data['id'] ) {
			\eoxia\LOG_Util::log( sprintf( __( 'Given comment identifier does not correspond to a comment in task manager. Request id: %s', 'task-manager-wpshop' ), $id ), 'task-manager-wpshop' );
			return false;
		}

		// Check if the comment must be added to current ticket .
		$comment->data['author'] = get_userdata( $comment->data['author_id'] );
		if ( in_array( 'administrator', $comment->data['author']->roles, true ) ) {
			\eoxia\LOG_Util::log( sprintf( __( 'The comment author role does not allowed support request. Request customer id: %d1$. Customer roles: %2$s', 'task-manager-wpshop' ), $comment->data['author_id'], wp_json_encode( $comment->data['author']->roles ) ), 'task-manager-wpshop' );
			return false;
		}

		// If the code continue from here it means that we have to set a new support request.
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );
		\eoxia\LOG_Util::log( sprintf( __( 'Current support request list: %s', 'task-manager-wpshop' ), wp_json_encode( $ids ) ), 'task-manager-wpshop' );
		\eoxia\LOG_Util::log( sprintf( __( 'Comment for adding in request %s', 'task-manager-wpshop' ), wp_json_encode( $comment ) ), 'task-manager-wpshop' );

		if ( empty( $ids[ $comment->data['post_id'] ] ) ) {
			$ids[ $comment->data['post_id'] ] = array(
				$comment->data['parent_id'] => array(
					$comment->data['id'],
				),
			);
		}

		if ( empty( $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] ) ) {
			$ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] = array(
				$comment->data['id'],
			);
		}

		if ( ! empty( $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] ) && ! in_array( $comment->data['id'], $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ], true ) ) {
			$ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ][] = (int) $comment->data['id'];
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
		\eoxia\LOG_Util::log( sprintf( __( 'New support ticket list: %s', 'task-manager-wpshop' ), wp_json_encode( $ids ) ), 'task-manager-wpshop' );

		return true;
	}

	/**
	 * Appelle la méthode remove_entry_customer_ask.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param integer $id L'ID du commentaire.
	 *
	 * @return void
	 */
	public function callback_tm_remove_entry_customer_ask( $id ) {
		Indicator_Class::g()->remove_entry_customer_ask( $id );
	}

	/**
	 * Met à jour le tableau des demandes des clients de WPShop.
	 *
	 * @since 1.3.0
	 * @version 1.3.0
	 *
	 * @param  Point_Model $point    Les données du point.
	 * @param  Task_Model  $old_task Les données de l'ancienne point ou la tâche était rattaché.
	 *
	 * @return void
	 */
	public function callback_tm_after_move_point_to( $point, $old_task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, array() );

		$tmp = array();

		if ( isset( $ids[ $old_task->data['id'] ] ) && isset( $ids[ $old_task->data['id'] ][ $point->data['id'] ] ) ) {
			$tmp = $ids[ $old_task->data['id'] ][ $point->data['id'] ];
			unset( $ids[ $old_task->data['id'] ][ $point->data['id'] ] );
		}

		if ( ! isset( $ids[ $point->data['post_id'] ][ $point->data['id'] ] ) && ! empty( $tmp ) ) {
			$ids[ $point->data['post_id'] ][ $point->data['id'] ] = $tmp;
		}

		update_option( \eoxia\Config_Util::$init['task-manager-wpshop']->key_customer_ask, $ids );
	}

	public function callback_mark_as_read() {
		check_ajax_referer( 'mark_as_read' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		Indicator_Class::g()->remove_entry_customer_ask( $id );

		wp_send_json_success( array(
			'namespace'        => 'taskManagerBackendWPShop',
			'module'           => 'indicator',
			'callback_success' => 'markedAsReadSuccess',
		) );
	}
}

new Indicator_Action();
