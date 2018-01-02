<?php
/**
 * Ajout de la liste des clients à notifier + la prévisualisation du message.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wphsop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>
	<h2>
		<?php esc_html_e( 'Customers', 'task-manager-wpshop' ); ?>
		(<span class="selected-number">0</span>/<span class="total-number"><?php echo esc_html( count( $users_id ) ); ?></span>)
	</h2>

	<ul class="list-customers">
		<?php
		if ( ! empty( $users_id ) ) :
			foreach ( $users_id as $user_id ) :
				?>
				<li class="follower" data-id="<?php echo esc_attr( $user_id ); ?>" style="width: 50px; height: 50px;">
					<?php echo do_shortcode( '[task_avatar ids=' . $user_id . ']' ); ?>
				</li>
				<?php
			endforeach;
		endif;
		?>
		<input type="hidden" name="customers_id" value="" />
	</ul>
</div>

<div>
	<h2><?php esc_html_e( 'Preview of notification', 'task-manager-wpshop' ); ?></h2>

	<h3><?php echo esc_html( $post->post_title ); ?></h3>
	<div><?php echo $body; ?></div>
</div>
