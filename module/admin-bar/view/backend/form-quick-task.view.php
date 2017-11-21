<?php
/**
 * Le formulaire pour créer une tâche rapide.
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
} ?>


<div>
	<p><?php esc_html_e( 'Quick task save on the post setted in task-manager-wpshop.config.json. This form create a comment on the point named <displayedname>.', 'task-manager-wpshop' ); ?></p>
	<form class="form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
		<input type="hidden" name="action" value="create_quick_task">
		<div style="width: 100%;">
			<textarea id="description" style="width: 100%" name="content" rows="6"></textarea>
		</div>

		<div style="float: right;">
			<input type="text" name="time" value="<?php echo esc_attr( empty( $comment->id ) && isset( $comment->time_info['calculed_elapsed'] ) ? $comment->time_info['calculed_elapsed'] : $comment->time_info['elapsed'] ); ?>" /> <?php esc_html_e( 'Min', 'task-manager-wpshop' ); ?><br />
		</div>

	</form>
</div>
