<?php
/**
 * Ajout de la checkbox pour confirmer la notification au client.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wphsop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="notify-label">
	<label for="notify_customer"><?php echo esc_html_e( 'Notify customer ?', 'task-manager-wpshop' ); ?></label>
	<input id="notify_customer" name="notify_customer" type="checkbox" />

	<div class="more-info hidden">

		<p>
			<?php
			// translators: You're going make a notify to the customer technique@eoxia.com.
			echo sprintf( __( 'You\'re gonna to send a notification to the customer %s', 'task-manager-wpshop' ), $user_info->user_email );
			?>
		</p>

		<p>
			<?php
			// translators: Mail subject: Nouve message sur votre support.
			echo sprintf( __( 'Mail subject: %s', 'task-manager-wpshop' ), $post->post_title );
			?>
		</p>

		<div>
			<?php echo $body; ?>
		</div>
	</div>


</div>
