<?php
/**
 * Gestion des filtres principaux de l'application.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.2.0
 * @version 1.2.0
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
		add_filter( 'task_manager_popup_notify_after', array( $this, 'callback_task_manager_popup_notify_after' ), 10, 2 );
		add_filter( 'task_manager_notify_send_notification_recipients', array( $this, 'callback_task_manager_notify_send_notification_recipients' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_subject', array( $this, 'callback_task_manager_notify_send_notification_subject' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_body', array( $this, 'callback_task_manager_notify_send_notification_body' ), 10, 3 );
	}


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

		ob_start();
		require( TM_WPS_PATH . '/core/view/notify/main.view.php' );
		$content .= ob_get_clean();
		return $content;
	}

	/**
	 * Ajoutes l'email du client WPShop lié à la tâche.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  array       $recipients Un tableau contenant l'email des utilisateurs liées à la tâche.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return array                   Le tableau contenant l'email des utilisateurs + celui du client.
	 */
	public function callback_task_manager_notify_send_notification_recipients( $recipients, $task, $form_data ) {
		if ( $form_data['notify_customer'] == 'false' ) {
			return $recipients;
		}

		$post = get_post( $task->parent_id );

		if ( ! $post ) {
			return $recipients;
		}

		$user_info = get_userdata( $post->post_author );
		$recipients[] = $user_info->user_email;

		return $recipients;
	}

	/**
	 * Modifie le sujet du mail envoyé au client.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  string      $subject    Le sujet du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le sujet du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_subject( $subject, $task, $form_data ) {
		if ( $form_data['notify_customer'] == 'false' ) {
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
	 * @version 1.2.0
	 *
	 * @param  string      $body    Le contenu du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le contenu du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_body( $body, $task, $form_data ) {
		if ( $form_data['notify_customer'] == 'false' ) {
			return $body;
		}

		$post = get_post( \eoxia\Config_Util::$init['task-manager-wpshop']->id_mail_support );

		if ( ! $post ) {
			return $body;
		}

		$body = $post->post_content;
		$datas = \task_manager\Activity_Class::g()->get_activity( array( $task->id ), 0 );
		$query = $GLOBALS['wpdb']->prepare( "SELECT ID FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
		$page_id = $GLOBALS['wpdb']->get_var( $query );
		$permalink = get_permalink( $page_id );
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/mail/list', array(
			'datas' => $datas,
			'last_date' => $last_date,
			'permalink' => $permalink,
		) );
		$body .= ob_get_clean();

		return $body;
	}
}

new Task_Manager_Wpshop_Core_Filter();
