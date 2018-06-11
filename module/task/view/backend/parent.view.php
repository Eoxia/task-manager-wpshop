<?php
/**
 * Le nom de l'élément parent à la tâche, affiché dans le header de la tâche
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager_wpshop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><li>
	<span class="wpeo-task-time-info wpeo-tooltip-event" aria-label="<?php echo esc_attr( $task_time_info_human_readable ); ?>">
		<i class="dashicons dashicons-admin-users"></i>
		<span><?php echo esc_html( $parent->post_title ); ?></span>
	</span>
</li>
