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

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="daily-activity activities">
	<div class="content">
		<?php if ( ! empty( $comments ) ) : ?>
			<?php foreach ( $comments as $comment ) : ?>

				<div class="activity">
					<div class="information">
						<?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="30"]' ); ?>
						<span class="time-posted"><?php echo esc_html( mysql2date( 'H\hi', $comment->date['date_input']['date'], true ) ); ?></span>
					</div>

					<div class="content">
						<div class="event-header">
							<!-- Client -->
								<span class="event-client">
									<i class="fa fa-user"></i>
									<?php if ( ! empty( $comment->post_parent->ID ) ) : ?>
									<a href="<?php echo esc_url( get_permalink( $comment->post_parent->ID ) ); ?>" target="wptm_view_activity_element" >
										<?php echo esc_html( '#' . $comment->post_parent->ID . ' ' . $comment->post_parent->post_title ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( '-' ); ?>
								<?php endif; ?>
								</span>
							<!-- Tâche -->
							<span class="event-task">
								<i class="dashicons dashicons-layout"></i> <?php echo esc_html( '#' . $comment->task->id . ' ' . $comment->task->title ); ?>
							</span>
							<!-- Point -->
							<span class="event-point">
								<i class="fa fa-list-ul"></i> <?php echo esc_html( '#' . $comment->point->id . ' ' . $comment->point->content ); ?>
							</span>
						</div>

						<span class="event-content">


							<?php
							$link = 'admin.php?page=wpeomtm-dashboard&term=' . $comment->task->id . '&point_id=' . $comment->point->id . '&comment_id=' . $comment->id;
							if ( ! empty( $comment->post_parent->id ) ) :
								$link = 'post.php?post=' . $comment->post_parent->id . '&term=' . $comment->task->id . '&action=edit&point_id=' . $comment->point->id . '&comment_id=' . $comment->id;
							endif;
							?>
							<a target="wptm_view_activity_element" href="<?php echo esc_url( admin_url( $link ) ); ?>" >
								<?php
								echo wp_kses( $comment->content, array(
									'br' => array(),
									'p' => array(),
								) );
								?>
							</a>
						</span>
					</div>
				</div>

			<?php endforeach; ?>
		<?php else : ?>
			<?php esc_html_e( 'No pending requests', 'task-manager-wpshop' ); ?>
		<?php endif; ?>
	</div><!-- .content -->
</div>
