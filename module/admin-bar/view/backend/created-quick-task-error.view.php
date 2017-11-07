<?php
/**
 * Lorsque la création d'une nouvelle tâche rapide à été rejeté.
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


<h2><?php echo esc_html_e( 'Error for created your quick task.', 'task-manager-wpshop' ); ?></h2>

<ul>
	<?php
	if ( ! empty( $errors_message ) ) :
		foreach ( $errors_message as $message ) :
			?>
				<li><?php echo esc_html( $message ); ?></li>
			<?php
		endforeach;
	endif;
	?>
</ul>

<span class="open-popup-ajax button"
			data-parent="js"
			data-target="popup"
			data-action="load_popup_quick_task"
			data-class="popup-quick-task"
			data-title="<?php esc_html_e( 'Quick task', 'task-manager-wpshop' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_popup_quick_task' ) ); ?>"><?php esc_html_e( 'Try again', 'task-manager-wpshop' ); ?></span>

<span class="button blue close"><?php esc_html_e( 'Close', 'task-manager-wpshop' ); ?></span>
