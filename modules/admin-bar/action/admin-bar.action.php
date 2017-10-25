<?php
/**
 * Fichier de gestion des actions de la barre d'administration.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des actions
 */
class Admin_Bar_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'wp_ajax_open_popup_last_wpshop_customer_ask', array( $this, 'callback_open_popup_last_wpshop_customer_ask' ) );
		add_action( 'wp_ajax_open_popup_last_wpshop_customer_comment', array( $this, 'callback_open_popup_last_wpshop_customer_comment' ) );

		add_action( 'wp_ajax_load_popup_quick_task', array( $this, 'callback_load_popup_quick_task' ) );
		add_action( 'wp_ajax_create_quick_task', array( $this, 'callback_create_quick_task' ) );
	}

	/**
	 * Permet d'afficher la dashicons qui vas être affiché dans la barre de WordPress.
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 *
	 * @return void
	 *
	 * @since 1.0.1
	 * @version 1.2.0
	 */
	public function callback_admin_bar_menu( $wp_admin_bar ) {
		if ( current_user_can( 'administrator' ) ) {
			Admin_Bar_Class::g()->init_quick_task( $wp_admin_bar );
			global $wpdb;
			$query_args = array(
				'action' => 'open_popup_last_wpshop_customer_ask',
				'width' => '1024',
				'height' => '768',
			);
			$comments = $wpdb->get_results( Admin_Bar_Class::g()->get_new_ask_query( 'POINT.comment_ID as point_id, POINT.comment_date, USERCOMMENTMETA.meta_value as user_level' ) ); // WPCS : unprepared sql ok.

			$current_date = current_time( 'timestamp' );
			$new_comment = '';
			$nb_comments = 0;
			if ( ! empty( $comments ) ) {
				foreach ( $comments as $comment ) {
					$timestamp_comment = mysql2date( 'U', $comment->comment_date );
					if ( ( $current_date - ( 3600 * 24 ) * 5 ) < $timestamp_comment ) {
						$new_comment = '🔴';
					}
					if ( 10 !== (int) $comment->user_level ) {
						$nb_comments += 1;
					}
				}
			}

			$button_open_popup = array(
				'id'			 	=> 'button-open-popup-last-ask-customer',
				'href'			=> '#',
				'title'			=> sprintf( __( '%1$s %2$d request', 'task-manager-wpshop' ), $new_comment, $nb_comments ),
				'meta'		 	=> array(
					'onclick' => 'tb_show( "Les derniers commentaires des clients WPShop", "' . add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) ) . '" )',
				),
			);
			$wp_admin_bar->add_node( $button_open_popup );

			$comments = $wpdb->get_results( Admin_Bar_Class::g()->get_new_response_query( 'POINT.comment_ID as point_id, POINT.comment_date' ) ); // WPCS: unprepared sql ok.
			$current_date = current_time( 'timestamp' );
			$new_comment = '';
			if ( ! empty( $comments ) ) {
				foreach ( $comments as $comment ) {
					$timestamp_comment = mysql2date( 'U', $comment->comment_date );
					if ( ( $current_date - ( 3600 * 24 ) * 5 ) < $timestamp_comment ) {
						$new_comment = '🔴';
						break;
					}
				}
			}
			$query_args = array(
				'action' => 'open_popup_last_wpshop_customer_comment',
				'width' => '1024',
				'height' => '768',
			);
			$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
			$button_open_popup = array(
				'id'			 	=> 'button-open-popup-last-comment-customer',
				'href'			=> '#',
				'title'			=> $new_comment . count( $comments ) . ' réponses',
				'meta'		 	=> array(
					'onclick' => 'tb_show( "Les derniers commentaires des clients WPShop", "' . $href . '")',
				),
			);
			$wp_admin_bar->add_node( $button_open_popup );
		} // End if().
	}


	/**
	 * Cette méthode appelle une vue qui sera renvoyé au rendu de la thickbox.
	 * Elle charge également les 5 derniers commentaires des clients de WPShop.
	 *
	 * @return void
	 *
	 * @since 1.0.1.0
	 * @version 1.0.1.0
	 */
	public function callback_open_popup_last_wpshop_customer_ask() {
		$customer_last_request = $GLOBALS['wpdb']->get_results( Admin_Bar_Class::g()->get_new_ask_query( 'TASK.ID AS task_id, TASK.post_title AS task_title, POINT.comment_ID as point_id, POINT.comment_content, POINT.comment_date, CUSTOMER.ID AS customer_id, CUSTOMER.post_title AS customer_name, POINT.user_id, COMMENT.user_id AS comment_user_id, USERCOMMENTMETA.meta_value as user_level' ) ); // WPCS: unprepared sql ok.

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'popup-ask-content', array(
			'requests' 	=> $customer_last_request,
			'format' 		=> '\L\e d F Y à H\hi',
		) );

		wp_die( ob_get_clean() ); // WPCS: XSS is ok.
	}

	/**
	 * Cette méthode appelle une vue qui sera renvoyé au rendu de la thickbox.
	 * Elle charge également les 5 derniers commentaires des clients de WPShop.
	 *
	 * @return void
	 *
	 * @since 1.0.1.0
	 * @version 1.0.1.0
	 */
	public function callback_open_popup_last_wpshop_customer_comment() {
		global $wpdb;
		$comments = $wpdb->get_results( Admin_Bar_Class::g()->get_new_response_query( 'USERCOMMENTMETA.user_id AS CUI, POINT.comment_content AS point_content, POINT.comment_ID AS point_id, TASK.post_parent, COMMENT.comment_ID, COMMENT.comment_content ,POINT.user_id, COMMENT.comment_date' ) ); // WPCS: unprepared sql ok.
		$format = '\L\e d F Y à H\hi';

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->comment_date = mysql2date( $format, $comment->comment_date );
			}
		}
		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'popup-comment', array(
			'comments' => $comments,
		) );
		wp_die( ob_get_clean() ); // WPCS: XSS is ok.
	}

	public function callback_load_popup_quick_task() {
		check_ajax_referer( 'load_popup_quick_task' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'backend/form-quick-task' );
		wp_send_json_success( array(
			'namespace' => 'taskManagerBackendWPShop',
			'module' => 'adminBar',
			'callback_success' => 'loadedPopupQuickTask',
			'view' => ob_get_clean(),
		) );
	}

	public function callback_create_quick_task() {
		check_ajax_referer( 'create_quick_task' );

		$content = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '';
		$parent_id = \eoxia\Config_Util::$init['task-manager-wpshop']->id_quick_task;

		if ( 0 === $parent_id || '' === $content || '' === $time ) {
			wp_send_json_error();
		}
		$current_user = wp_get_current_user();
		$task = \task_manager\Task_Class::g()->get( array(
			'post_parent' => $parent_id,
			'name' => 'unclassified',
		), true );
		if ( empty( $task ) ) {
			$task = \task_manager\Task_Class::g()->update( array(
				'parent_id' => $parent_id,
				'title' => __( 'Unclassified', 'task-manager-wpshop' ),
			) );
		}
		$point = \task_manager\Point_Class::g()->get( array(
			'user_id' => $current_user->ID,
			'post__in' => $task->id,
			'status' => -34070,
			'parent' => 0,
		), true );
		if ( 0 === $point->id ) {
			$point = \task_manager\Point_Class::g()->update( array(
				'status' => '-34070',
				'author_id' => $current_user->ID,
				'post_id' => $task->id,
				'content' => $current_user->user_login,
			) );
			$task->task_info['order_point_id'][] = (int) $point->id;
			\task_manager\Task_Class::g()->update( $task );
		}
		$time = \task_manager\Task_Comment_Class::g()->update( array(
			'status' => '-34070',
			'content' => $content,
			'post_id' => $task->id,
			'parent_id' => $point->id,
			'author_id' => $current_user->ID,
			'time_info' => array(
				'elapsed' => $time,
			),
		) );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager-wpshop', 'admin-bar', 'backend/created-quick-task-success' );
		wp_send_json_success( array(
			'namespace' => 'taskManagerBackendWPShop',
			'module' => 'adminBar',
			'callback_success' => 'createdQuickTask',
			'view' => ob_get_clean(),
		) );
	}

}

new Admin_Bar_Action();
