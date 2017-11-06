<?php
/**
 * Ajout du partage du lien pour les clients.
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

<li>
	<a href="<?php echo esc_attr( $token_url . '&term=' . $comment->post_id . '&point_id=' . $comment->parent_id . '&comment_id=' . $comment->id ); ?>"><?php esc_html_e( 'Share link', 'task-manager-wpshop' ); ?></a>
</li>
