<?php
/**
 *
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package support
 * @subpackage view
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) { exit; }

View_Util::exec( 'support', 'frontend/comment/edit', array(
	'task_id' => $task_id,
	'point_id' => $point_id,
	'comment' => $comment_schema,
) );

View_Util::exec( 'support', 'frontend/comment/list-comment', array(
	'comments' => $comments,
) );
