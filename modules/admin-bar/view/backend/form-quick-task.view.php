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
	<form class="form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
		<input type="hidden" name="action" value="create_quick_task">
		<label for="description"><?php esc_html_e( 'A description', 'task-manager-wpshop' ); ?></label>
		<textarea id="description" name="content" rows="6"></textarea>
		<input type="text" name="time" value="15" />
		<input type="button" data-loader="form" class="action-input" data-parent="form" data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_quick_task' ) ); ?>" value="<?php esc_html_e( 'Create quick task', 'task-manager-wpshop' ); ?>">
	</form>
</div>
