<?php
/**
 * Le contenu de la popup contenant les derniers commentaires des clients WPShop.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.0.1.0
 * @version 1.0.1.0
 * @copyright 2017 Eoxia
 * @package Task Manager Ticket
 * @subpackage template
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php if ( ! empty( $requests ) ) : ?>
<ul class="tm-wps-customer-requests" >
<?php foreach ( $requests as $request ) :	?>
	<?php if ( 10 !== (int) $request->user_level ) : ?>
	<li class="tm-wps-customer-requests-customer" >
		<span><a target="wps_customer" href="<?php echo esc_url( admin_url( 'post.php?post=' . $request->customer_id . '&action=edit' ) ); ?>" >#<?php echo esc_html( $request->customer_id ); ?> - <?php echo esc_html( $request->customer_name ); ?></a></span>
		<ul>
			<li>
				<span><a target="task_search" href="<?php echo esc_url( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $request->task_id . '' ) ); ?>" >#<?php echo esc_html( $request->task_id ); ?> - <?php echo esc_html( $request->task_title ); ?></a></span>
				<ul>
					<li class="tm-wpshop-customer-request-point-infos" >
						<span>#<?php echo esc_html( $request->point_id ); ?></span>
						<span><?php echo esc_html( mysql2date( $format, $request->comment_date ) ); ?></span>
						<span><?php
							$request_user_info = get_userdata( $request->user_id );
							echo esc_html( $request_user_info->user_email . ' - ' . $request_user_info->display_name );
						?></span>
					</li>
					<li class="tm-wps-customer-request-point-content" >
						<span><?php echo esc_html( nl2br( trim( $request->comment_content ) ) ); ?></span>
					</li>
				</ul>
			</li>
		</ul>
	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php else : ?>
	<?php esc_html_e( 'No customer waiting request for the moment', 'task-manager-wpshop' ); ?>
<?php endif; ?>
