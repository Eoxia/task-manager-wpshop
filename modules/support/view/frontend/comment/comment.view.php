<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li class="comment">
	<ul>
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="16"]' ); ?></li>
		<li class="wpeo-comment-date"><?php echo esc_html( $comment->author->display_name ) . ', ' . esc_html( $comment->date ); ?></li>
		<li class="wpeo-comment-content"><?php echo esc_html( $comment->content ); ?></li>
	</ul>
</li>
