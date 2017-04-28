<?php
/**
 * La liste des commentaires dans le frontend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.0.0.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! empty( $comments ) ) :
	foreach ( $comments as $comment ) :
		if ( 0 !== $comment->id ) :
			View_Util::exec( 'support', 'frontend/comment/comment', array(
				'comment' => $comment,
			) );
		endif;
	endforeach;
endif;
