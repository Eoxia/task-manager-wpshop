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
	<span <?php echo esc_attr( ! empty( $parent_title ) ? 'class="wpeo-tooltip-event" aria-label="' . $parent_title . '"' : '' ); ?>>
		<i class="dashicons <?php echo esc_attr( $parent_icon ); ?>"></i>
		<span><?php echo esc_html( $parent->post_title ); ?></span>
	</span>
</li>
