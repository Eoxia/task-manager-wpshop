<?php
/**
 * Le contenu de la popup contenant les derniers commentaires des clients WPShop.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.0.1
 * @version 1.2.0
 * @copyright 2017 Eoxia
 * @package Task_Manager_WPShop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( ! empty( $comments ) ) : ?>
	<ul class="tm-wps-customer-requests">
		<?php
		foreach ( $comments as $comment ) :
			?>
			<li>
				<span><?php echo esc_html( $comment->date['date_human_readable'] ); ?></span>
			</li>

			<?php if ( null !== $comment->post_parent ) : ?>
				<li class="tmp-wps-customer-requests-customer">
					<span>
						<?php esc_html_e( 'On the customer', 'task-manager-wpshop' ); ?>
						<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $comment->post_parent->ID . '&action=edit' ) ); ?>">
							#<?php echo esc_html( $comment->post_parent->ID ); ?>
							 - <?php echo esc_html( $comment->post_parent->post_title ); ?>
						</a>
					</span>
				</li>
			<?php endif; ?>
			<li>
				<span>
					<?php esc_html_e( 'On the task', 'task-manager-wpshop' ); ?>
					<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $comment->task->id . '&action=edit' ) ); ?>">
						#<?php echo esc_html( $comment->task->id ); ?>
						- <?php echo esc_html( $comment->task->title ); ?>
					</a>
					</span>
				</li>
				<li>
					<span>
						<?php esc_html_e( 'On the point', 'task-manager-wpshop' ); ?>
						<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $comment->task->id . '&action=edit&point_id=' . $comment->point->id ) ); ?>">
							#<?php echo esc_html( $comment->point->id ); ?>
							- <?php echo esc_html( $comment->point->content ); ?>
						</a>
					</span>
				</li>
				<li>
					<span>
						<?php echo esc_html_e( 'With the comment:', 'task-manager-wpshop' ); ?>
						<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $comment->task->id . '&action=edit&point_id=' . $comment->point->id . '&comment_id=' . $comment->id ) ); ?>">
							#<?php echo esc_html( $comment->id ); ?>
							- <?php echo esc_html( $comment->content ); ?>
						</a>
					</span>
				</li>
			<?php
		endforeach;
		?>
	</ul>
<?php else : ?>
	<?php esc_html_e( 'No customer waiting request for the moment', 'task-manager-wpshop' ); ?>
<?php endif; ?>
