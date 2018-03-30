<?php
/**
 * Gestion des filtres principaux de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2017
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Task_Manager_Wpshop_Core_Filter {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 *
	 * @since 0.1.0
	 * @version 1.2.0
	 */
	public function __construct() {
		add_filter( 'tm_task_main_menu_title', array( $this, 'callback_tm_task_main_menu_title' ) );
		add_filter( 'task_manager_popup_notify_after', array( $this, 'callback_task_manager_popup_notify_after' ), 10, 2 );
		add_filter( 'task_manager_notify_send_notification_recipients', array( $this, 'callback_task_manager_notify_send_notification_recipients' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_subject', array( $this, 'callback_task_manager_notify_send_notification_subject' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_body', array( $this, 'callback_task_manager_notify_send_notification_body' ), 10, 3 );
		add_filter( 'tm_comment_toggle_before', array( $this, 'callback_tm_comment_toggle_before' ), 10, 2 );
	}

	/**
	 * Renvoies le titre du menu "Task" modifié avec le nombre de demande des clients.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  string $title Le titre du menu.
	 * @return string        Le titre du menu modifié.
	 */
	public function callback_tm_task_main_menu_title( $title ) {
		$number_ask = Support_Class::g()->get_number_ask();

		if ( $number_ask > 0 ) {
			// $title .= '<span class="wp-core-ui wp-ui-notification"><span>' . $number_ask . '</span></span>';
		}
		return $title;
	}

	/**
	 * Ajoutes du contenu de la popup "notification".
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string     $content Le contenu de la popup.
	 * @param  Task_Model $task    Les données de la tâche.
	 *
	 * @return string              Le contenu de la popup modifié.
	 */
	public function callback_task_manager_popup_notify_after( $content, $task ) {
		if ( 0 === $task->parent_id ) {
			return $content;
		}

		$post_type = get_post_type( $task->parent_id );

		if ( ! $post_type ) {
			return $content;
		}

		if ( 'wpshop_customer' === $post_type ) {
			return false;
		}

		$post = get_post( \eoxia\Config_Util::$init['task-manager-wpshop']->id_mail_support );
		$body = __( 'No support post found', 'task-manager-wpshop' );
		if ( ! empty( $post->post_content ) ) {
			$body = $post->post_content;
		}
		$datas     = \task_manager\Activity_Class::g()->get_activity( array( $task->id ), 0 );
		$query     = $GLOBALS['wpdb']->prepare( "SELECT ID FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
		$page_id   = $GLOBALS['wpdb']->get_var( $query );
		$permalink = get_permalink( $page_id );
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/mail/list', array(
			'datas'     => $datas,
			'last_date' => '',
			'permalink' => $permalink,
		) );
		$body .= ob_get_clean();

		$users_id = get_post_meta( $task->parent_id, '_wpscrm_associated_user', true );

		if ( empty( $users_id ) ) {
			$users_id = array();
		}

		$customer_post = get_post( $task->parent_id );

		if ( ! empty( $customer_post ) && ! in_array( $customer_post->post_author, (array) $users_id ) ) {
			$users_id[] = $customer_post->post_author;
		}

		ob_start();
		require TM_WPS_PATH . '/core/view/notify/main.view.php';
		$content .= ob_get_clean();
		return $content;
	}

	/**
	 * Ajoutes l'email du client WPShop lié à la tâche.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  array       $recipients Un tableau contenant l'email des utilisateurs liées à la tâche.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return array                   Le tableau contenant l'email des utilisateurs + celui du client.
	 */
	public function callback_task_manager_notify_send_notification_recipients( $recipients, $task, $form_data ) {
		if ( empty( $form_data['customers_id'] ) ) {
			return $recipients;
		}

		$post = get_post( $task->parent_id );

		if ( ! $post ) {
			return $recipients;
		}

		$customers_id = explode( ',', $form_data['customers_id'] );

		foreach ( $customers_id as $user_id ) {
			$user_info    = get_userdata( $user_id );
			$recipients[] = $user_info->user_email;
		}

		return $recipients;
	}

	/**
	 * Modifie le sujet du mail envoyé au client.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string      $subject    Le sujet du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le sujet du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_subject( $subject, $task, $form_data ) {
		if ( empty( $form_data['customers_id'] ) ) {
			return $subject;
		}

		$post = get_post( \eoxia\Config_Util::$init['task-manager-wpshop']->id_mail_support );

		if ( ! $post ) {
			return $subject;
		}

		$subject = $post->post_title;
		return $subject;
	}

	/**
	 * Modifie le contenu du mail envoyé au client.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string      $body    Le contenu du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le contenu du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_body( $body, $task, $form_data ) {
		if ( empty( $form_data['customers_id'] ) ) {
			return $body;
		}

		$post = get_post( \eoxia\Config_Util::$init['task-manager-wpshop']->id_mail_support );

		if ( ! $post ) {
			return $body;
		}

		$body      = $post->post_content;
		$datas     = \task_manager\Activity_Class::g()->get_activity( array( $task->id ), 0 );
		$query     = $GLOBALS['wpdb']->prepare( "SELECT ID FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
		$page_id   = $GLOBALS['wpdb']->get_var( $query );
		$permalink = get_permalink( $page_id );
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/mail/list', array(
			'datas'     => $datas,
			'last_date' => '',
			'permalink' => $permalink,
		) );
		$body .= ob_get_clean();

		return $body;
	}

	public function callback_tm_comment_toggle_before( $view, $comment ) {
		if ( 0 === $comment->post_id || ! class_exists( 'TokenLogin' ) ) {
			return $view;
		}

		$task = \task_manager\Task_Class::g()->get( array(
			'id' => $comment->post_id,
		), true );

		if ( 0 === $task->parent_id ) {
			return $view;
		}

		$post_type = get_post_type( $task->parent_id );

		if ( ! $post_type ) {
			return $view;
		}

		if ( 'wpshop_customer' === $post_type ) {
			return $view;
		}

		$cpt_customer = get_post( $task->parent_id );

		$login_token = \TokenLogin::getToken( $cpt_customer->post_author );
		$token_url = \TokenLogin::getTokenUrl( $cpt_customer->post_author, $login_token );

		ob_start();
		require( TM_WPS_PATH . '/core/view/comment/before-toggle.view.php' );
		$view .= ob_get_clean();
		return $view;
	}
}

new Task_Manager_Wpshop_Core_Filter();
