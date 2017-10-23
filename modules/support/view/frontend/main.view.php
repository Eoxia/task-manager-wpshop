<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wpeo-project-wrap">
	<h2><?php esc_html_e( 'A request ?', 'task-manager-wpshop' ); ?></h2>

	<p>
		<?php esc_html_e( 'Ask your question. We will answer you on the opened ticket', 'task-manager-wpshop' ); ?>
		<span class="button blue">
			<i class="fa fa-ticket" aria-hidden="true"></i>
			<span class="open-popup-ajax"
						data-title="<?php echo esc_html_e( 'Ask your question', 'task-manager-wpshop' ); ?>"
						data-parent="wpeo-project-wrap"
						data-target="popup"
						data-action="open_popup_create_ticket"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'open_popup_create_ticket' ) ); ?>"><?php esc_html_e( 'Open a ticket', 'task-manager-wpshop' ); ?></span>
		</span>
	</p>

	<h2><?php esc_html_e( 'Support', 'task-manager-wpshop' ); ?></h2>

	<p>
		<span><?php esc_html_e( 'Last activity on your support the: ', 'task-manager-wpshop' ); ?></span>
		<span><?php echo esc_html( $last_modification_date ); ?></span>
	</p>

	<span class="open-popup-ajax"
				data-parent="wpeo-project-wrap"
				data-target="popup"
				data-action="load_last_activity"
				data-title="Last activities"
				data-tasks-id="<?php echo esc_attr( $tasks_id ); ?>"
				data-frontend="1">
		<i class="fa fa-list" aria-hidden="true"></i>
		<span><?php esc_html_e( 'See the latest activities performed', 'task-manager-wpshop' ); ?></span>
	</span>

	<?php \eoxia\View_Util::exec( 'task-manager-wpshop', 'support', 'frontend/popup' ); ?>

	<?php \task_manager\Task_Class::g()->display_tasks( $tasks, true ); ?>
</div>
