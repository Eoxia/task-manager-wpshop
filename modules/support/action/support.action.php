<?php
/**
 * Fichier de gestion des actions
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Classe de gestion des actions
 */
class Support_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_front_comments', array( $this, 'callback_load_front_comments' ) );
		add_action( 'wp_ajax_nopriv_load_front_comments', array( $this, 'callback_load_front_comments' ) );

		add_action( 'wp_ajax_edit_comment_front', array( $this, 'callback_edit_comment_front' ) );
		add_action( 'wp_ajax_nopriv_edit_comment_front', array( $this, 'callback_edit_comment_front' ) );

		add_action( 'wp_ajax_ask_task', array( $this, 'callback_ask_task' ) );
		add_action( 'wp_ajax_nopriv_ask_task', array( $this, 'callback_ask_task' ) );
	}

	public function callback_load_front_comments() {
		// check_ajax_referer( 'load_front_comments' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		$comments = \task_manager\Task_Comment_Class::g()->get( array(
			'post_id' => $task_id,
			'parent' => $point_id,
			'status' => '-34070',
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->author = get_userdata( $comment->author_id );
			}
		}

		$comment_schema = \task_manager\Task_Comment_Class::g()->get( array(
			'schema' => true,
		), true );

		ob_start();
		View_Util::exec( 'support', 'frontend/comment/main', array(
			'task_id' => $task_id,
			'point_id' => $point_id,
			'comments' => $comments,
			'comment_schema' => $comment_schema,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'view' => $view,
			'module' => 'frontendSupport',
			'callback_success' => 'loadedFrontComments',
		));
	}

	public function callback_edit_comment_front() {
		check_ajax_referer( 'edit_comment_front' );

		$comment_id = ! empty( $_POST['comment_id'] ) ? (int) $_POST['comment_id'] : 0;
		$post_id = ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';
		$time = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;

		$comment = \task_manager\Task_Comment_Class::g()->update( array(
			'id' => $comment_id,
			'post_id' => $post_id,
			'parent_id' => $parent_id,
			'date' => current_time( 'mysql' ),
			'content' => $content,
			'time_info' => array(
				'elapsed' => $time,
			),
		) );

		$comment->author = get_userdata( $comment->author_id );

		ob_start();
		View_Util::exec( 'support', 'frontend/comment/comment', array(
			'comment' => $comment,
		) );

		wp_send_json_success( array(
			'time' => array(
				'point' => $comment->point->time_info['elapsed'],
				'task' => $comment->task->time_info['elapsed'],
			),
			'view' => ob_get_clean(),
			'module' => 'frontendSupport',
			'callback_success' => ! empty( $comment_id ) ? 'editedCommentSuccess' : 'addedCommentSuccess',
			'comment' => $comment,
		) );
	}

	public function callback_ask_task() {
		check_ajax_referer( 'ask_task' );
		global $wpdb;

		$edit = false;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE post_name=%s";
		$list_task = $wpdb->get_results( $wpdb->prepare( $query, array( 'ask-task-' . get_current_user_id() ) ) );
		/** On crée la tâche */
		if ( 0 === count( $list_task ) ) {
			$task = \task_manager\Task_Class::g()->update(
				array(
					'title' => __( 'Ask', 'task-manager' ),
					'slug' => 'ask-task-' . get_current_user_id(),
					'parent_id' => \wps_customer_ctr::get_customer_id_by_author_id( get_current_user_id() ),
				)
			);
			$task_id = $task->id;
		} else {
			$edit = true;
			$task_id = $list_task[0]->ID;
		}
		$task = \task_manager\Task_Class::g()->get( array(
			'include' => array( $task_id ),
		), true );

		$_POST['point']['author_id'] = get_current_user_id();
		$_POST['point']['status'] = '-34070';
		$_POST['point']['date'] = current_time( 'mysql' );
		$_POST['point']['post_id'] = $task_id;

		$point = \task_manager\Point_Class::g()->update( $_POST['point'] );

		$task->task_info['order_point_id'][] = (int) $point->id;
		\task_manager\Task_Class::g()->update( $task );

		ob_start();
		require( PLUGIN_TASK_MANAGER_PATH . '/module/task/view/frontend/task.view.php' );
		wp_send_json_success( array(
			'task_id' => $task_id,
			'edit' => $edit,
			'module' => 'frontendSupport',
			'callback_success' => 'askedTask',
			'template' => ob_get_clean(),
		) );
	}
}

new Support_Action();
