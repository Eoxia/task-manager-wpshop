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

<table>
	<tr>
		<th style="width: 10%">ID</th>
		<th style="width: 20%">Date</th>
		<th style="width: 20%">Email</th>
		<th>Contenu du point</th>
		<th>Contenu de la réponse</th>
	</tr>

	<?php if ( ! empty( $comments ) ) :
		foreach ( $comments as $comment ) :
			?>
			<tr>
				<td style="vertical-align: top;"><?php echo esc_html( $comment->comment_ID ); ?></td>
				<td style="vertical-align: top;"><?php echo esc_html( $comment->comment_date ); ?></td>
				<td style="vertical-align: top;">
					<a target="_blank" href="<?php echo esc_attr( admin_url( 'post.php?post=' . $comment->post_parent . '&action=edit' ) ); ?>">
						<?php echo esc_html( $comment->user_email ); ?>
					</a>
				</td>
				<td style="vertical-align: top;"><?php echo nl2br( esc_html( $comment->point_content ) ); ?></td>
				<td style="vertical-align: top;"><?php echo nl2br( esc_html( $comment->comment_content ) ); ?></td>
		<?php endforeach;
	endif; ?>
</ul>
