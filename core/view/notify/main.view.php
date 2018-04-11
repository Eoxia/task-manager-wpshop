<?php
/**
 * Ajout de la liste des clients à notifier + la prévisualisation du message.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.3.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wphsop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>
	<h2>
		<?php esc_html_e( 'Contacts', 'task-manager-wpshop' ); ?>
		(<span class="selected-number">0</span>/<span class="total-number"><?php echo esc_html( count( $users_id ) ); ?></span>)
	</h2>

	<ul class="list-customers wpeo-ul-users">
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

	<?php	if ( ! empty( $post ) ) : ?>
		<h3><?php echo esc_html( $post->post_title ); ?></h3>
		<div><?php echo $body; ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'No support post defined', 'task-manager-wpshop' ); ?>
	<?php endif; ?>

</div>
